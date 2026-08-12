<?php

namespace App\Services\Image;

use App\Jobs\ProcessImageJob;
use App\Models\Image;
use App\Models\User;
use App\Repositories\Image\ImageRepository;
use App\Services\BaseService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * @class ImageService
 *
 * @package App\Services\Image
 */
class ImageService extends BaseService
{
    /**
     * __construct
     *
     * @param ImageRepository $repository
     */
    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * index
     *
     * @param User $user
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function index(User $user, int $perPage = 15): LengthAwarePaginator
    {
        return $user->images()->paginate($perPage);
    }

    /**
     * show
     *
     * @param Image $image
     * @return Image
     */
    public function show(Image $image): Image
    {
        return $image;
    }

    /**
     * create
     *
     * @param User $user
     * @param UploadedFile|null $file
     * @return array|null
     */
    public function create(User $user, ?UploadedFile $file): ?array
    {
        if (!$file) {
            return null;
        }

        $tempPath = $file->store('temp_uploads');

        ProcessImageJob::dispatch(
            $user,
            $tempPath,
            $file->getClientOriginalName()
        );

        return [
            'message' => 'Image upload received.',
            'status' => 'processing',
        ];
    }

    /**
     * delete
     *
     * @param User $user
     * @param Image $image
     * @return array
     */
    public function delete(User $user, Image $image): array
    {
        DB::transaction(function () use ($user, $image) {
            $user->images()->detach($image->id);

            if (!$image->users()->exists()) {
                Storage::delete($image->path);
                $image->delete();
            }
        });

        return [
            'message' => 'The image has been deleted successfully',
        ];
    }
}
