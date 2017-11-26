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



Route::group(['prefix' => 'v1'], function () {

    Route::get('image/{image}', 'ImageController@getPost');
    Route::get('/', 'Api\ApiController@sayHello');
    Route::post('register', 'Api\UserController@register');
    Route::post('login', 'Api\UserController@authenticate');
    Route::resource('films', 'Api\FilmController');

    Route::middleware('auth:api')->post('/comment', 'Api\FilmController@comment');
});
