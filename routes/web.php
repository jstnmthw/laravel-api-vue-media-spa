<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/sitemap/videos/{page}', 'SitemapController@media');

Route::get('/{vue_capture?}', function () {
    $criticalCss = @file_get_contents(public_path('css/critical.min.css'));
    $data['criticalCss'] = $criticalCss ?: null;
    return view('master', $data);
})->where('vue_capture', '[\/\w\.-]*');
