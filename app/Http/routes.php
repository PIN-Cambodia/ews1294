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
// use File;

Route::get('/home', function () {
    return view('index');
});

Route::post('postPhonesFromReminderGroup', ['as' => 'phones.insert', 'uses' => 'GetPhonesFromCallLogCtrl@getPhoneCallLog']);
Route::get('getPhonesFromReminderGroup', ['uses' => 'GetPhonesFromCallLogCtrl@getPhoneCallLog']);
// Route::get('/register_new_contact', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContact']);

Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function () {
      //  Route::post('/short', 'UrlMapperController@store');
      // Route::get('/register_new_contact', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContact']);
      Route::post('/register_new_contact', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContact']);
   });

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
// Login
Route::get('/login', function () {
    return view('auth/login');
});
Route::post('login', ['as' => 'auth.login', 'uses' => 'UserauthController@loginAuth']);

// Register
Route::get('/register', function () {
    return view('auth/register');
});
// Reset Password
Route::get('/reset', function () {
    return view('auth/passwords/reset');
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

// Receiving Call Log API
// Route::get('receivingcalllog', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);

Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function()
{
    //Route::get('receivingcalllog/{calllog_data}', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);
    Route::post('receivingcalllog', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);
});

// CallLog report
Route::get('calllogreport', ['uses' => 'CallLogReportController@CallLogReportView']);
Route::post('getCallLogReport', ['uses' => 'CallLogReportController@getCallLogReport']);


// Password Reset Routes...
// Route::get('password/reset/{token?}', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@showResetForm']);
// Route::post('password/email', ['as' => 'auth.password.email', 'uses' => 'Auth\PasswordController@sendResetLinkEmail']);
// Route::post('password/reset', ['as' => 'auth.password.reset', 'uses' => 'Auth\PasswordController@reset']);

//***
//Get the phone numbers in which commune(s).
//***
Route::get('/phoneNumbersSelectedByCommunes', function()
{
    $commune_ids = Input::get('commune_ids');
    $phoneNumbersInCommunes = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$commune_ids))->get();
    // $phoneNumbersInCommunes = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$commune_ids))->count();
    //$data = json_encode($phoneNumbersInCommunes);
    // $fileName = time() . '_datafile.json';
    // File::put(public_path($fileName),$data);
    // return public_path($fileName);
    // return Response::download(public_path($fileName));
    //return Response::json($data);
    return Response::json($phoneNumbersInCommunes);
});

//***
// Insert new activity after sending sound file and contacts.
//***
Route::get('/add_new_activity', ['uses' => 'SoundfileCtrl@insertNewActivity']);
//***
// Display Wiki page of how to use API in EWS system.
//***
Route::get('/wiki', function () {
    return view('apiWiki');
});

Route::get('/', function () {
   return redirect('/home');
});
