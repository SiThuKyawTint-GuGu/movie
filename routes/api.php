<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\AuthorController;
use App\Http\Controllers\Api\v1\CommentController;
use App\Http\Controllers\Api\v1\GenresController;
use App\Http\Controllers\Api\v1\MovieController;
use App\Http\Controllers\Api\v1\TagController;

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

Route::prefix('v1')->group(function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/profile', [AuthController::class, 'detail']);

    Route::middleware('auth:api')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);

        Route::prefix('users')->group(function () {

        });

        Route::prefix('movies')->controller(MovieController::class)->group(function () {
            Route::post('/','store');
            Route::get('/', 'index');
            Route::put('/{id}','update');
            Route::delete('/{id}','destroy');
            Route::get('/{id}', 'details');
            Route::get('/related-movies/{id}','relatedMovie');
        });

        Route::prefix('authors')->controller(AuthorController::class)->group(function () {
            Route::post('/', 'store');
        });
        Route::prefix('genres')->controller(GenresController::class)->group(function () {
            Route::post('/', 'store');
        });
        Route::prefix('tags')->controller(TagController::class)->group(function () {
            Route::post('/', 'store');
        });
        Route::prefix('comments')->controller(CommentController::class)->group(function () {
            Route::post('/', 'store');
        });
    });
    Route::get('comments/list', [CommentController::class, 'list']);
});


