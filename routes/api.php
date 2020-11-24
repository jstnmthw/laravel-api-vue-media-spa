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
        // Authentication passed...
        return Response::make('Success', 201);
    } else {
        return Response::make('Failed', 401);
    }
});
Route::prefix('media')->group(function() {
    Route::get('categories/{slug}', 'MediaController@category');
    Route::get('related', 'MediaController@related');
    Route::get('best', 'MediaController@best');
    Route::get('search', 'MediaController@search');
    Route::get('{slug}', 'MediaController@title');
    Route::get('/', 'MediaController@index');
    Route::post('{id}/dislike', 'MediaController@dislike');
    Route::post('{id}/like', 'MediaController@like');
});
Route::apiResource('categories', 'CategoryController');
