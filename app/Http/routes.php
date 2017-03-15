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
use App\Models\Sensors;
use Illuminate\Support\Facades\Input;

Route::get('/home', function () {
    return view('index');
});

/**
 * Route to post reminder group id to import phone numbers from Verboice.
 */
Route::post('postPhonesFromReminderGroup', ['as' => 'phones.insert', 'uses' => 'GetPhonesFromCallLogCtrl@importPhoneContactsFromVerboice']);

/**
* Route to get phone numbers from Verboice to be imported into EWS Server.
* Note: It will duplicate phone numbers that have already been inserted,
* So make sure that the selected reminder group's phone numbers have not been imported from Verboice before.
*/
Route::get('getPhonesFromReminderGroup', ['uses' => 'GetPhonesFromCallLogCtrl@importPhoneContactsFromVerboice']);

/** A group of routing which requires API User account */
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function () {
    // Register New Contact API
    Route::post('/register_new_contact', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContact']);
    // Call Logs API
    Route::post('/receivingcalllog', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);
    // Sensor Logs API
    Route::post('sensorapi', ['uses' => 'Sensor\ReceivingSensorInfoAPIController@sensorAPI']);
}); // .'middleware' => ['auth', 'auth:api']

/** user needs to log in in order to access the following routes */
Route::group(['middleware' => ['auth']], function(){
    /** --- CallLog report ---  */
    Route::get('/calllogreport', ['uses' => 'CallLogReportController@CallLogReportView']);
    Route::post('/getCallLogReport', ['uses' => 'CallLogReportController@getCallLogReport']);
    Route::post('/getSSCommunes', ['uses' => 'Sensor\SensorsController@getSSCommunesPerDistrict']);
    Route::post('/getSSList', ['uses' => 'CallLogReportController@getSensorListInSelectedProvince']);

    // Upload Sound File
    Route::get('/soundFile', function () {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
        {
            if (App::getLocale()=='km')
                $provinces = DB::table('province')->select('PROCODE','PROVINCE_KH')->get();
            else
                $provinces = DB::table('province')->select('PROCODE','PROVINCE')->get();
        }
        if(Auth::user()->hasRole('PCDM'))
        {
            $pcdm_province=DB::table('role_user')->where('user_id', Auth::user()->id)->first();

            if (App::getLocale()=='km')
                $provinces = DB::table('province')->select('PROCODE','PROVINCE_KH')
                    -> where('PROCODE', $pcdm_province->province_code)
                    ->get();
            else
                $provinces = DB::table('province')->select('PROCODE','PROVINCE')
                    -> where('PROCODE', $pcdm_province->province_code)
                    ->get();
        }
        return view('uploadSoundFile',['provinces' => $provinces]);
    });

    // Get list of province for PCDM role in Registration view
    Route::post('get_authorized_province', ['uses' => 'UserauthController@getAuthorizedProvince']);

    /** --- Showing call log report for specific activity ID --- */
    // web url/call_logActivity?activID=3
    Route::get('/calllogActivity', 'CallLogReportController@getCallLogReportPerActivity');
}); // .'middleware' => ['auth']

/** Only Admin has rights to access the following routes */
Route::group(['middleware' => ['auth', 'admin.auth']], function(){

    /** --- User Registration --- */
    Route::get('/register', function () {
        return view('auth/register');
    });
    Route::post('register', ['as' => 'auth.register', 'uses' => 'UserauthController@registerAuth']);

    /** --- User Management --- */
    // list of users
    Route::get('/allusers', ['as' => 'allusers', 'uses' => 'UserauthController@userLists']);
    // User Profile
    Route::post('/userprofile', ['uses' => 'UserauthController@displayUserProfiles']);
    // Save Edited User data
    Route::post('/saveuserdata', ['uses' => 'UserauthController@saveUserProfile']);
    // Enable/Disable User
    Route::post('/enabledisable', ['uses' => 'UserauthController@enableDisable']);
    // Delete User
    Route::post('/deleteuser', ['uses' => 'UserauthController@deleteUser']);

    // Display Wiki page of how to use API in EWS system.
    Route::get('/wiki', function () {
        return view('apiWiki');
    });

    /**
     * Route to get all sensors.
     */
    Route::get('/sensors', function () {
        $sensors = DB::table('sensors')->get();
        $all_province = DB::table('province')->get();
        return view('sensors',['sensors' => $sensors, 'all_province' => $all_province]);
    });
    // Display Sensor Info by Id
    Route::post('sensor_info', ['uses' => 'Sensor\SensorsController@displaySensorInfoById']);
    // Save Change Sensor Info
    Route::post('save_change_sensor_info', ['uses' => 'Sensor\SensorsController@saveChangeSensorInfo']);
    // Get All Sensors' Info
    Route::get('getSensors', 'Sensor\SensorsController@getSensors');
    // Delete Sensor Info
    Route::post('delete_sensor_info', ['uses' => 'Sensor\SensorsController@deleteSensor']);
    // Add Sensor Info
    Route::post('add_new_sensor_info', ['uses' => 'Sensor\SensorsController@addNewSensor']);

    /** --- Sensor Trigger --- */
    Route::get('/sensortrigger', ['uses' => 'Sensor\SensorTriggerController@sensorTriggerReport']);
    Route::post('/addsensortrigger', ['uses' => 'Sensor\SensorTriggerController@addSensorTrigger']);
    // Sensor Trigger Report
    Route::get('/sensortrigger', ['uses' => 'Sensor\SensorTriggerController@sensorTriggerReport']);
    // Sensor Trigger Add, Edit, and Delete
    Route::post('/getDistricts', ['uses' => 'Sensor\SensorTriggerController@getDistrictPerProvince']);
    Route::post('/getCommunes', ['uses' => 'Sensor\SensorTriggerController@getCommunesPerDistrict']);
    Route::post('/addsensortrigger', ['uses' => 'Sensor\SensorTriggerController@addSensorTrigger']);
    Route::post('/geteditsensortrigger', ['uses' => 'Sensor\SensorTriggerController@getEditSensorTrigger']);
    Route::post('/saveeditsensortrigger', ['uses' => 'Sensor\SensorTriggerController@saveEditSensorTrigger']);
    Route::post('/deletesensortrigger', ['uses' => 'Sensor\SensorTriggerController@deleteSensorTrigger']);
    Route::get('/getAllProvinces', ['uses' => 'Sensor\SensorTriggerController@getAllProvinces']);
}); // .'middleware' => ['auth', 'admin.auth']

/**
 * Route to get districts of province $pro_id.
 */
Route::get('/districts', function()
{
  $pro_id = Input::get('pro_id');
  $districs = DB::table('district')->select('DCode','DName_kh')->where('PCode',$pro_id)->get();
  return Response::json($districs);
});

/**
 * Route to get communes of district $dis_id.
 */
Route::get('/communes', function()
{
  $dis_id = Input::get('dis_id');
  $communes = DB::table('commune')->select('CCode','CName_kh')->where('DCode',$dis_id)->get();
  return Response::json($communes);
});

/**
 * Route to get districts and communes of province $pro_id.
 */
Route::get('/disNcom', function()
{
    $pro_id = Input::get('pro_id');
    if (App::getLocale()=='km')
    {
        $districs = DB::table('district')
            ->join('commune','district.DCode','=','commune.DCode')
            ->select('district.DCode','DName_kh AS DName','CCode','CName_kh AS CName')->where('PCode',$pro_id)->where('district.status',1)->get();
    }
    else
    {
        $districs = DB::table('district')
            ->join('commune','district.DCode','=','commune.DCode')
            ->select('district.DCode','DName_en AS DName','CCode','CName_en AS CName')->where('PCode',$pro_id)->where('district.status',1)->get();
    }
  return Response::json($districs);
});
/**
 * Route to get the number of communes under which district.
 */
Route::get('/numberOfcommunes', function()
{
  $district_id = Input::get('district_id');
  $districs = DB::table('district')
  ->join('commune','district.DCode','=','commune.DCode')
  ->select('district.DCode','DName_kh','CCode','CName_kh','CName_en')->where('district.DCode',$district_id)->where('district.status',1)->get();
  return Response::json($districs);
});


/**
 * Get the number of phones in which commune.
*/
Route::get('/numberOfPhones', function()
{
    $commune_id = Input::get('commune_id');
    $noOfPhones = DB::table('targetphones')
        ->select(DB::raw('COUNT(phone) as phone'))->where('commune_code',$commune_id)->get();
    return $noOfPhones;
});

/**
 * Make a call to those phone numbers in which commune, district and/or province.
 */
Route::post('/callThem', ['as' => 'call.them','uses' => 'GetPhonesFromCallLogCtrl@callThem']);

/** --- Login, Logout --- */
Route::get('/login', function () {
    return view('auth/login');
});
Route::post('/login', ['as' => 'auth.login', 'uses' => 'UserauthController@loginAuth']);
// Logout
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'UserauthController@logoutAuth']);

/** --- Reset Password --- */
// show reset password form
Route::get('/password.email', function () {
    return view('auth/passwords/email');
});
// send email to reset password
Route::post('/password.email', ['uses' => 'Auth\PasswordController@sendResetLinkEmail']);
// Reset Password
Route::post('/password.reset', ['uses' => 'Auth\PasswordController@reset']);
// Get value of token and email for reset password
Route::get('/password.reset/{token?}', ['uses' => 'Auth\PasswordController@getReset']);

/**
* Get the phone numbers in which commune(s).
*/
Route::get('/phoneNumbersSelectedByCommunes', function()
{
    $commune_ids = Input::get('commune_ids');
    $phoneNumbersInCommunes = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$commune_ids))->get();
    return Response::json($phoneNumbersInCommunes);
});

/**
 * Route to insert new activity after sending sound file and contacts.
 */
Route::post('/add_new_activity', ['uses' => 'SoundfileCtrl@insertNewActivity']);

// Redirect to home when user type ews1294.info
Route::get('/', function () {
   return redirect('/home');
});

// Change Language locale on click of flag icon
Route::post('changelang', ['uses' => 'UserauthController@changeLanguage']);

/**
 * Route to display sensor report of first 24 readings for $sensor_id.
 */
Route::get('/sensorsLog20', function () {
    $sensor_id = Input::get('sensor_id');
    $sensorlogs = DB::table('sensorlogs')->where('sensor_id','=',$sensor_id)->orderBy('timestamp','desc')->limit(24)->get();
    return view('sensorsLogReport',['sensorlogs' => $sensorlogs, 'reportPage' => '1', 'sensorId' => $sensor_id]);
});

/**
 * Route to display sensor report of 30 days for $sensor_id.
 */
Route::get('/sensorsLog1thReadingOf30days', function () {
    $sensor_id = Input::get('sensor_id');
    $sensorlogs = DB::table('sensorlogs')
        ->select(DB::raw("date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, id, sensor_id, stream_height, charging, voltage ,timestamp"))
        ->where('sensor_id','=',$sensor_id)
        ->groupBy('time')
        ->orderBy('timestamp','desc')->limit(30)->get();
    return view('sensorsLogReport',['sensorlogs' => $sensorlogs, 'reportPage' => '2', 'sensorId' => $sensor_id]);
});

/**
 * Route to display Sensor Map
 */
Route::get('/sensormap', function () {
    $sensorIds=sensors::select('sensor_id','location_coordinates')->get()->toArray();
    $sensorlogsAll = array();
    $i=0;
    foreach($sensorIds as $sensorId)
    {
        $sensorlogs = DB::table('sensorlogs')
            ->join('sensors','sensorlogs.sensor_id','=','sensors.sensor_id')
            ->join('sensortriggers','sensortriggers.sensor_id','=','sensorlogs.sensor_id')
            ->select('sensorlogs.id as id','sensortriggers.sensor_id as sensor_id', 'stream_height', 'charging', 'voltage' ,'timestamp', 'sensortriggers.level_warning as warning_level', 'sensortriggers.level_emergency as emergency_level','sensors.location_coordinates',DB::raw("timestampdiff(HOUR, timestamp, NOW())"),DB::raw("NOW()"))
            ->where('sensorlogs.sensor_id','=',$sensorId['sensor_id'])
            ->orderBy('sensorlogs.timestamp','desc')
            ->orderBy('sensorlogs.sensor_id')
            ->where (DB::raw("timestampdiff(HOUR, timestamp, NOW())"),'<=','24')
            ->limit(1)
            ->get();
        if(sizeof($sensorlogs)>0)
        {
            $sensorlogsAll[] = $sensorlogs[0];
        }
    }
    return view('sensorsMap',['sensors' => $sensorIds, 'sensors24hrs' => $sensorlogsAll]);
});

/**
 * Route for retrieve all communes for one provice, then check all communes checkbox.
 */
Route::get('/checkall', function()
{
    $pro_id = Input::get('pro_id');
        $districs = DB::table('district')
            ->join('commune','district.DCode','=','commune.DCode')
            ->join('targetphones','targetphones.commune_code','=','commune.CCode')
            ->select(DB::raw('COUNT(phone) as phone'))
            ->where('PCode',$pro_id)->where('district.status',1)->get();
    return $districs;
});

/**
 * Route to display sensor data on Chart
 */
Route::get('/sensorlogReportInChart', ['uses' => 'sensorLogChartCtrl@createChart']);

/*
****Route to display about EWS
*/

 Route::get('/about', function(){
    return view('/aboutUs');
 });


    Route::get('/sensorLogChart', 'Sensor\SensorsController@getSensors');
 

