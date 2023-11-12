<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return 'asd';
});

Route::get('/signup', [AuthController::class, 'signUp']);
Route::get('/login', [AuthController::class, 'login']);

