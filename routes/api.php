<?php

use Illuminate\Http\Request;
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



Route::group(['namespace' => 'Api'], function() {
    Route::prefix('user')->group(function() {
        Route::post('register', 'AuthController@register');
        Route::post('token', 'AuthController@token');
    });

    Route::group(['middleware' => ['auth:sanctum']], function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        Route::get('/car/my', 'CarController@my')->name('car.my');
        Route::resource('/car', CarController::class, ['except' => ['create', 'edit']]);
        
        
    });
});



