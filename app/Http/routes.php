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
Route::get('/register', function () {
    return view('auth/register');
});
// Route::auth();
// Route::get('/home', 'HomeController@index');

// Route::get('/uauth', 'UserauthController@index');

Route::get('/soundFile', function () {
    return view('uploadSoundFile');
});


// Route::get('/uauth', 'UserauthController@index');
// Route::post('/uauth', 'UserauthController@loginauth');
//Route::get('login', ['as' => 'auth.login', 'uses' => 'UserauthController@showLoginForm']);
Route::post('login', ['as' => 'auth.login', 'uses' => 'UserauthController@loginauth']);
Route::post('register', ['as' => 'auth.register', 'uses' => 'UserauthController@register']);
//Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@logout']);

// Route::get('login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@showLoginForm']);
// Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
// Route::get('logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@logout']);

// Registration Routes...
// Route::get('register', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@showRegistrationForm']);
// Route::post('register', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@register']);

// Password Reset Routes...
// Route::get('password/reset/{token?}', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@showResetForm']);
// Route::post('password/email', ['as' => 'auth.password.email', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);
// Route::post('password/reset', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@reset']);
