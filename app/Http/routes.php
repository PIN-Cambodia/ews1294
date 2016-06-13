<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/home', function () {
    return view('index');
});


/* Login, Regiser, Reset password, and Send confirm Email */
Route::get('/login', function () {
    return view('auth/login');
});
// Route::auth();
// Route::get('/home', 'HomeController@index');
Route::post('/uauth', 'UserauthController@index');



Route::get('/soundFile', function () {
    return view('uploadSoundFile');
});
