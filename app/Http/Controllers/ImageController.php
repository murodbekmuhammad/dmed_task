<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        //
    }

    public function show(Request $request)
    {
//        $request->validate([
//            'image' => 'required|image|mimes:jpeg,png|max:2048'
//        ]);
//
//        $path = $request->file('image')->store('images');
//        dd($path);
    }

    public function create(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:2048' /// why jpg is being skipped?
        ]);

        $user = auth()->user();

//        $user->images()->attach()
        $file = $request->file('image');
        $file->store('images');

        $uploadedFile = Image::create([
            'original_name' => $file->getClientOriginalName(),
            'path' => $file->path(),
            'hash' => 'hash',
            'size' => $file->getSize(),
            'status' => 'draft'
        ]);

        $user->images()->attach($uploadedFile->id);

        return response()->successJson($uploadedFile);
    }

    public function delete(Request $request)
    {
//
    }
}
