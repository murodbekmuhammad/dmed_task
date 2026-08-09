<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->prefix('images')->group(function () {
    Route::get('/', [ImageController::class,'index']);
    Route::get('/{image}', [ImageController::class,'show']);

    Route::post('/', [ImageController::class,'create']);
    Route::delete('/{image}', [ImageController::class,'delete']);
});
