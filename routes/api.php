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

/**
 * Protected
 */
Route::group(['middleware' => ['auth:sanctum', 'throttle:60,1']], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});

/**
 * Unprotected
 */
Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        // Authentication passed...
        return Response::make('Success', 201);
    } else {
        return Response::make('Failed', 401);
    }
});

/**
 * Resources
 */
Route::get('videos/search', 'VideoController@search');
Route::get('videos/category', 'VideoController@category');
Route::get('videos/best', 'VideoController@best');

Route::post('videos/{id}/like', 'VideoController@like');
Route::post('videos/{id}/dislike', 'VideoController@dislike');

Route::apiResource('videos', 'VideoController');
Route::apiResource('categories', 'CategoryController');
