<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\DataCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Data;

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
    $data = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);
    Auth::attempt($data);
    return Response::make('', 204);
});

// API Resources
Route::apiResource('videos', 'VideoController');
Route::get('db', 'VideoController@update_db');
// Route::get('videos/categories/{cat}', 'VideoController@categories')->where('cat', '[A-Za-z\-]+');

Route::apiResource('categories', 'CategoryController');

