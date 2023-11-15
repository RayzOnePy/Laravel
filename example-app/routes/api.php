<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/files', [FileController::class, 'store']);
    Route::get('/files/disk', [FileController::class, 'showAll']);
    Route::get('/files/{fileName}', [FileController::class, 'download']);
    Route::patch('/files/{fileName}', [FileController::class, 'edit']);
    Route::delete('/files/{fileName}', [FileController::class, 'delete']);

    Route::get('/logout', [AuthController::class, 'logout']);
});

Route::post('/registration', [AuthController::class, 'signUp']);
Route::post('/authorization', [AuthController::class, 'login']);
