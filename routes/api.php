<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
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

/**
 * Protected API
 */
Route::group(['middleware' => ['auth:sanctum', 'throttle:60,1']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

/**
 * Unprotected API
 */
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        return Response::make('Successfully Authenticated', 201);
    } else {
        return Response::make('Failed Authenticating', 401);
    }
});

Route::group(['middleware' => ['throttle:60,1']], function () {
    Route::prefix('media')->group(function () {
        Route::get('categories/{slug}', 'MediaController@category');
        Route::get('related', 'MediaController@related');
        Route::get('best', 'MediaController@best');
        Route::get('search', 'MediaController@search');
        Route::get('collect', 'MediaController@collect');
        Route::get('most-viewed', 'MediaController@mostViewed');
        Route::get('recommended', 'MediaController@mostLikes');
        Route::get('{key}', 'MediaController@getByKey');
        Route::get('/', 'MediaController@index');
        Route::post('{id}/dislike', 'MediaController@dislike');
        Route::post('{id}/like', 'MediaController@like');
    });
    Route::apiResource('categories', 'CategoryController');
});