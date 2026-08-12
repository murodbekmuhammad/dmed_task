<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Requests\Image\CreateImageRequest;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

/**
 * @class ImageController
 *
 * @package App\Http\Controllers
 */
class ImageController extends Controller
{
    /**
     * __construct
     *
     * @param ImageService $service
     */
    public function __construct(protected ImageService $service) {}

    /**
     * index
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        return response()->successJson($this->service->index($request->user()));
    }

    /**
     * show
     *
     * @param Image $image
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Image $image)
    {
        return response()->successJson($this->service->show($image));
    }

    /**
     * store
     *
     * @param CreateImageRequest $request
     * @return JsonResponse
     */
    public function store(CreateImageRequest $request)
    {
        $file = $request->file('image');

        return response()->successJson($this->service->create($request->user(), $file));
    }

    /**
     * destroy
     *
     * @param Request $request
     * @param Image $image
     * @return JsonResponse
     */
    public function destroy(Request $request, Image $image)
    {
        return response()->successJson($this->service->delete($request->user(), $image));
    }
}
