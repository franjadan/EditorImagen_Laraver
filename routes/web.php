<?php

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

Route::get('/', 'SpotController@create');

Route::get('/anuncio/nuevo', 'SpotController@create')->name('spots.create');
Route::post('/anuncio/nuevo', 'SpotController@store');
Route::post('/anuncio/subir_imagen', 'SpotController@uploadImage')->name('spots.upload');



// MediaManager
ctf0\MediaManager\MediaRoutes::routes();