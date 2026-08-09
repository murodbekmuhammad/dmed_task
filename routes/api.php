<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->prefix('images')->group(function () {
    Route::get('/', [ImageController::class,'index']);
    Route::get('/{image}', [ImageController::class,'show'])->middleware('can:view,image');

    Route::post('/', [ImageController::class,'store']);
    Route::delete('/{image}', [ImageController::class,'destroy'])->middleware('can:delete,image');
});
