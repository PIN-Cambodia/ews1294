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
use Illuminate\Support\Facades\Input;

Route::get('/home', function () {
    return view('index');
});

// Route::post('getPhonesFromReminderGroup', ['as' => 'phones.insert', 'uses' => 'GetPhonesFromCallLogCtrl@getPhoneCallLog']);
Route::get('/register_new_contact', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContact']);

Route::get('/getPhones', ['uses' => 'GetPhonesFromCallLogCtrl@getPhones']);

Route::get('/extractTargetPhones', function () {
    $reminderGroups = DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
    //var_dump($provinces);
    return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
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
    $provinces = DB::table('province')->select('PROCODE','PROVINCE_KH')->get();
    //var_dump($provinces);
    return view('uploadSoundFile',['provinces' => $provinces]);
});

Route::get('/districts', function()
{
  $pro_id = Input::get('pro_id');
  $districs = DB::table('district')->select('DCode','DName_kh')->where('PCode',$pro_id)->get();
    //var_dump($districs);
  return Response::json($districs);
});

Route::get('/communes', function()
{
  $dis_id = Input::get('dis_id');
  $communes = DB::table('commune')->select('CCode','CName_kh')->where('DCode',$dis_id)->get();
    //var_dump($districs);
  return Response::json($communes);
});

Route::get('/disNcom', function()
{
  $pro_id = Input::get('pro_id');
  $districs = DB::table('district')
  ->join('commune','district.DCode','=','commune.DCode')
  ->select('district.DCode','DName_kh','CCode','CName_kh','CName_en')->where('PCode',$pro_id)->where('district.status',1)->get();
    //var_dump($districs);
  return Response::json($districs);
});

//***
//Get the number of communes under which district.
//***
Route::get('/numberOfcommunes', function()
{
  $district_id = Input::get('district_id');
  $districs = DB::table('district')
  ->join('commune','district.DCode','=','commune.DCode')
  ->select('district.DCode','DName_kh','CCode','CName_kh','CName_en')->where('district.DCode',$district_id)->where('district.status',1)->get();
    //var_dump($districs);
  return Response::json($districs);
});

//***
//Get the number of phones in which commune.
//***
Route::get('/numberOfPhones', function()
{
  $commune_id = Input::get('commune_id');
  $noOfPhones = DB::table('targetphones')
  ->select('phone')->where('commune_code',$commune_id)->get();
  return Response::json($noOfPhones);
});

//***
//Make a call to those phone numbers in which commune, district and/or province.
//***
Route::post('/callThem', ['as' => 'call.them','uses' => 'GetPhonesFromCallLogCtrl@callThem']);

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
