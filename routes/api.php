<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

require __DIR__ . '/auth.php';

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware(['auth:api']);

Route::group([
    'middleware' => 'jwt.verify',
    // 'middleware' => 'jwt.auth',
    // 'middleware' => 'auth:api',
    'prefix' => 'v1/',
    'as'     => 'api.v1.',
], function () {
    Route::get('users', [UserController::class, 'index'])
        ->name('users');
});

Route::any('{any}', function () {
    return response()->json([
        'status'  => 'error',
        'message' => 'Resource not found',
    ], Response::HTTP_NOT_FOUND);
})->where('any', '.*');
