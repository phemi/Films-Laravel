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

Route::get('/', function () {
    return redirect()->intended('films');
});

Auth::routes();

Route::get('/films', 'HomeController@index')->name('home');
Route::get('/films/create', 'HomeController@createFilm')->name('create_film');
Route::get('/films/{film_slug}', 'HomeController@viewFilm')->name('films');
Route::post('/films/create', 'HomeController@storeFilm')->name('create_film');
Route::post('/comment-on-film', 'HomeController@comment');
