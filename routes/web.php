<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Sitemap\Sitemap;
use App\Media;

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

Route::get('/sitemap', function() {

});

Route::get('/test', function() {

    echo Sitemap::create()
        ->add(url('/videos/test'))
        ->renderTags();
});

Route::get('/{vue_capture?}', function () {
    app('debugbar')->disable();
    return view('master');
})->where('vue_capture', '[\/\w\.-]*');
