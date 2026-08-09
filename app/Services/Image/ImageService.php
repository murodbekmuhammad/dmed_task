<?php

namespace App\Services\Image;

use App\Models\Image;
use App\Repositories\Image\ImageRepository;
use App\Services\BaseService;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ImageService extends BaseService
{
    public function __construct(ImageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return auth()->user()?->images ?? [];
    }

    public function show(Image $image): Image
    {
        return $image;
    }

    public function create($file)
    {
        if(!$file){
            return null;
        }

        $user = auth()->user();
        $hash = hash_file(
            'sha256',
            $file->getRealPath()
        );

        $image = Image::where('hash', $hash)->first();

        if (!$image) {
            $manager = new ImageManager(new Driver());

            $processedImage = $manager->read(
                $file->getRealPath()
            );

            $path = "images/{$hash}.webp";

            Storage::put(
                $path,
                $processedImage->toWebp(90)
            );

            $image = Image::create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'hash' => $hash,
                'size' => Storage::size($path),
                'status' => 'draft',
            ]);
        }

        $user->images()->syncWithoutDetaching($image->id);

        return $image;
    }

    public function delete(Image $image): array
    {
        $user = auth()->user();
        $user->images()->detach($image->id);

        $image->refresh();

        if(!$image->users()->exists()){
            Storage::delete($image->path);
            $image->delete();
        }

        return [
            'message'=>'The image has been deleted successfully'
        ];
    }
}
