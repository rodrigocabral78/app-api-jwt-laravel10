<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\UserRegistrationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix'     => 'v1/auth/',
    'as'         => 'api.v1.auth',
], function () {
    Route::group([
        'middleware' => 'guest',
    ], function () {
        Route::post('login', [AuthenticationController::class, 'store'])->name('login');
        Route::post('in', [AuthenticationController::class, 'store'])->name('in');

        Route::post('register', [UserRegistrationController::class, 'store'])->name('register');
    });

    Route::group([
        // 'middleware' => 'jwt.auth',
        'middleware' => 'auth:api',
    ], function () {
        Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');
        Route::post('out', [AuthenticationController::class, 'destroy'])->name('out');

        Route::post('refresh', [AuthenticationController::class, 'update'])->name('refresh');

        Route::post('me', [AuthenticationController::class, 'show'])->name('me');
    });
});

// Route::apiResource('posts', PostController::class)->middleware('auth:sanctum');
