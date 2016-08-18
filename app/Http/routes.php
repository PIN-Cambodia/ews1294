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

// Login
Route::get('/login', function () {
    return view('auth/login');
});
Route::post('login', ['as' => 'auth.login', 'uses' => 'UserauthController@loginAuth']);

// Register
Route::get('/register', function () {
    return view('auth/register');
});
Route::post('register', ['as' => 'auth.register', 'uses' => 'UserauthController@registerAuth']);

// Logout
Route::get('logout', ['as' => 'auth.logout', 'uses' => 'UserauthController@logoutAuth']);

// list of users
Route::get('allusers', ['as' => 'allusers', 'uses' => 'UserauthController@userLists']);

// User Profile
Route::post('userprofile', ['uses' => 'UserauthController@displayUserProfiles']);

// Save Edited User data
Route::post('saveuserdata', ['uses' => 'UserauthController@saveUserProfile']);

// Enable/Disable User
Route::post('enabledisable', ['uses' => 'UserauthController@enableDisable']);

// Delete User
Route::post('deleteuser', ['uses' => 'UserauthController@deleteUser']);



// Password Reset Routes...
// Route::get('password/reset/{token?}', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@showResetForm']);
// Route::post('password/email', ['as' => 'auth.password.email', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);
// Route::post('password/reset', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@reset']);
