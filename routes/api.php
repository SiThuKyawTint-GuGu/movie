<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\AuthController;
use App\Http\Controllers\Api\v1\MovieController;

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

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/profile', [AuthController::class, 'detail']);

    Route::middleware('auth:api')->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Authentication
        |--------------------------------------------------------------------------
        */
        Route::post('/logout', [AuthController::class, 'logout']);

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->group(function () {

        });

        /*
        |--------------------------------------------------------------------------
        | Movies
        |--------------------------------------------------------------------------
        */
        Route::prefix('movies')
            ->controller(MovieController::class)
            ->group(function () {
                Route::get('/','list');
                Route::post('/','create');
                Route::put('/{id}','update');
                Route::get('/{id}','detail');
                Route::delete('/{id}','destroy');
            });
    });

});
