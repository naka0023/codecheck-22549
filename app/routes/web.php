<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', 'LoginController@oauth');
Route::get('/new', 'NewController@showUserInfo');
Route::post('/new', 'NewController@createEvent');

Route::get('/email', 'EmailController@showUserInfo');
Route::post('/email', 'EmailController@sendEmail');
