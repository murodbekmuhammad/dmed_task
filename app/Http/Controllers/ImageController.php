<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Requests\Image\CreateImageRequest;
use App\Services\Image\ImageService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function __construct(protected ImageService $service) {}

    public function index(Request $request)
    {
        return response()->successJson($this->service->index($request->user()));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Image $image)
    {
        return response()->successJson($this->service->show($image));
    }

    public function store(CreateImageRequest $request)
    {
        $file = $request->file('image');

        return response()->successJson($this->service->create($request->user(), $file));
    }

    public function destroy(Request $request, Image $image)
    {
        return response()->successJson($this->service->delete($request->user(), $image));
    }
}
