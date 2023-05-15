<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\PostController;

use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\FollowingController;

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

Route::any('/', function () {
    return response()->json([
        'documentation' => 'https://www.postman.com/security-operator-67555345/workspace/fal-public/collection/17468817-ca88d6df-f428-4de0-9793-baa59d850eca',
        'created_by' => 'Falih Irsyadi Muzakky'
    ], 200);
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
     
Route::middleware('auth:api')->group(function () {
    Route::resource('profile', ProfileController::class);
    Route::resource('follow', FollowingController::class);
    Route::resource('users', UserController::class);
    Route::resource('posts', PostController::class);
});
