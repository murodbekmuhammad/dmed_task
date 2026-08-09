<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $userImages = auth()->user()?->images ?? [];

        return response()->successJson($userImages);
    }

    public function show(Image $image)
    {
        return response()->successJson($image);
    }

    public function create(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        $user = auth()->user();

        $file = $request->file('image');
        $hash = hash('sha256', file_get_contents($file->getRealPath()));
        $image = Image::query()->where('hash', $hash)->first();

        if(!$image){
            $file->store('images');

            $image = Image::create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $file->path(),
                'hash' => $hash,
                'size' => $file->getSize(),
                'status' => 'draft'
            ]);
        }

        $user->images()->syncWithoutDetaching($image->id);

        return response()->successJson($image);
    }

    public function delete(Image $image)
    {
        $user = auth()->user();
        $user->images()->detach($image->id);

        return response()->successJson(['message'=>'The image has been deleted successfully']);
    }
}
