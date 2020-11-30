<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/login', 'LineController@login')->name('login');
Route::get('/logout', 'LineController@logout')->name('logout');
Route::get('/photo', 'PhotoController@photo')->name('photo');
Route::post('/photo/upload', 'PhotoController@upload')->name('upload');