<?php

use App\Http\Controllers\Api\V1\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group([

    'middleware' => 'jwt',
    'prefix' => '/v1/auth'

], function ($router) {

    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
});

Route::post('/v1/auth/login', [AuthController::class, 'login'])->withoutMiddleware(['throttle:api']);
Route::post('/v1/auth/refresh', [AuthController::class, 'refresh']);
