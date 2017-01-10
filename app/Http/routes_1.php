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
use App\Models\sensortriggers;
use App\Models\targetphones;
use Illuminate\Http\Request;
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

Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function () {
    Route::get('/register_new_contact_test', ['uses' => 'GetPhonesFromCallLogCtrl@registerNewContactTest']);
});


Route::get('/getPhones', ['uses' => 'GetPhonesFromCallLogCtrl@getPhones']);

Route::get('/extractTargetPhones', function () {
    $reminderGroups = DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
    //var_dump($provinces);
    return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
});

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
})->middleware('auth');

Route::get('/sensors', function () {
    $sensors = DB::table('sensors')->get();
    if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM')) $all_province = DB::table('province')->get();
    elseif(Auth::user()->hasRole('PCDM'))
    {
        $pcdm_province=DB::table('role_user')->where('user_id', Auth::user()->id)->first();
        $all_province=DB::table('province')->where('PROCODE', $pcdm_province->province_code)->get();
    }
    //var_dump($sensors); die();
    return view('sensors',['sensors' => $sensors, 'all_province' => $all_province]);
})->middleware('auth');

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
//Get the number of phones in which commune.
//***
Route::get('/numberOfPhonesUpdate', function()
{
    $commune_id = Input::get('commune_id');
    $noOfPhones = DB::table('targetphones')
        ->select(DB::raw('COUNT(phone) as phone'))->where('commune_code',$commune_id)->get();
    return $noOfPhones;
});

//***
//Make a call to those phone numbers in which commune, district and/or province.
//***
Route::post('/callThem', ['as' => 'call.them','uses' => 'GetPhonesFromCallLogCtrl@callThem']);

/** ----- User Registration, Login, Logout, Reset Password and User Management ------ **/
// Register
Route::get('/register', function () {
    if(Auth::user()->hasRole('admin'))
        return view('auth/register');
    else return redirect()->intended('home');

})->middleware('auth');
Route::post('register', ['middleware' => 'auth', 'as' => 'auth.register', 'uses' => 'UserauthController@registerAuth']);

// Login
Route::get('/login', function () {
    return view('auth/login');
});
Route::post('/login', ['as' => 'auth.login', 'uses' => 'UserauthController@loginAuth']);

// Logout
Route::get('/logout', ['as' => 'auth.logout', 'uses' => 'UserauthController@logoutAuth']);

// Reset Password
// show reset password form
Route::get('/password.email', function () {
    return view('auth/passwords/email');
});

// send email to reset password
Route::post('/password.email', ['uses' => 'Auth\PasswordController@sendResetLinkEmail']);

// Reset Password
Route::post('/password.reset', ['uses' => 'Auth\PasswordController@reset']);

// Get value of token and email for reset password
// Route::get('password.reset/{token?}', ['uses' => 'Auth\PasswordController@showResetForm']);
Route::get('/password.reset/{token?}', ['uses' => 'Auth\PasswordController@getReset']);

// list of users
Route::get('/allusers', ['middleware' => 'auth', 'as' => 'allusers', 'uses' => 'UserauthController@userLists']);

// User Profile
Route::post('/userprofile', ['middleware' => 'auth', 'uses' => 'UserauthController@displayUserProfiles']);

// Save Edited User data
Route::post('/saveuserdata', ['middleware' => 'auth', 'uses' => 'UserauthController@saveUserProfile']);

// Enable/Disable User
Route::post('/enabledisable', ['middleware' => 'auth', 'uses' => 'UserauthController@enableDisable']);

// Delete User
Route::post('/deleteuser', ['middleware' => 'auth', 'uses' => 'UserauthController@deleteUser']);

// Receiving Call Log API
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function()
{
    //Route::get('receivingcalllog/{calllog_data}', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);
    Route::post('/receivingcalllog', ['uses' => 'ReceivingCallLogAPIController@callLogAPI']);
});

// CallLog report
Route::get('/calllogreport', ['uses' => 'CallLogReportController@CallLogReportView'])->middleware('auth');
Route::post('/getCallLogReport', ['middleware' => 'auth', 'uses' => 'CallLogReportController@getCallLogReport']);

//***
//Get the phone numbers in which commune(s).
//***
Route::get('/phoneNumbersSelectedByCommunes', function()
{
    $commune_ids = Input::get('commune_ids');
    $phoneNumbersInCommunes = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$commune_ids))->get();
    return Response::json($phoneNumbersInCommunes);
});

Route::get('/phoneNumbersSelectedByCommunesTest', function()
{
    $commune_ids = Input::get('commune_ids');
    $targetphones_tbl = new targetphones;
    $phoneNumbersInCommunes = $targetphones_tbl->select('phone')->whereIn('commune_code',explode(",",$commune_ids))->get();

    $phoneNumbers_officers = \DB::table('sensortriggers')->select('phone_numbers as phone')->where('sensor_id','1020301')->get();

    $splitArray = explode(",",$phoneNumbers_officers[0]->phone);
    foreach ($splitArray as $splitArrayEach)
    {
        $phoneNumbersInCommunes->push(['phone'=> $splitArrayEach]);
    }

    return Response::json($phoneNumbersInCommunes);
});

//***
// Insert new activity after sending sound file and contacts.
//***
Route::post('/add_new_activity', ['uses' => 'SoundfileCtrl@insertNewActivity']);
//***
// Display Wiki page of how to use API in EWS system.
//***
Route::get('/wiki', function () {
    return view('apiWiki');
})->middleware('auth');

Route::get('/', function () {
    return redirect('/home');
});

// ------------ Sensor ---------------------------
// Sensor API
Route::group(['prefix' => 'api/v1', 'middleware' => 'auth:api'], function()
{
    Route::post('sensorapi', ['uses' => 'Sensor\ReceivingSensorInfoAPIController@sensorAPI']);
    //Route::get('sensorapi', ['uses' => 'Sensor\ReceivingSensorInfoAPIController@sensorAPI']);
});

// get list of province for PCDM role in Registration view
Route::post('get_authorized_province', ['middleware' => 'auth','uses' => 'UserauthController@getAuthorizedProvince']);
// Change Language locale on click of flag icon
Route::post('changelang', ['uses' => 'UserauthController@changeLanguage']);

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
// Display Sensor Map
Route::post('sensors_map_old', ['uses' => 'Sensor\SensorsController@addNewSensor']);

Route::get('/sensormapOld', function () {
    $sensors = DB::table('sensors')->get();
//var_dump($provinces); die();
    return view('sensorsMap',['sensors' => $sensors]);
});

/*Route::get('/sensormap', function () {
    $sensors = DB::table('sensors')
        ->rightJoin('sensorlogs','sensorlogs.sensor_id','=','sensors.sensor_id')
        ->rightJoin('sensortriggers','sensortriggers.sensor_id','=','sensors.sensor_id')
//        ->select('sensors.id', 'sensors.sensor_id','sensors.location_code','sensors.additional_location_info','sensors.location_coordinates','sensortriggers.level_emergency as emergency_level','sensortriggers.level_warning as warning_level','sensorlogs.stream_height')
        ->select('sensors.id', 'sensors.sensor_id','sensortriggers.level_emergency as emergency_level','sensortriggers.level_warning as warning_level','sensorlogs.stream_height','sensors.location_coordinates')
        ->orderBy('sensorlogs.timestamp','ASC')
        ->groupBy('sensors.sensor_id')
//        ->max('sensorlogs.timestamp');
        ->get();
//var_dump($sensors); die();
    return view('sensorsMap',['sensors' => $sensors]);
});*/

Route::get('/sensormapTest', function () {
    $sensors = DB::table('sensorlogs')
//        ->rightJoin('sensorlogs','sensorlogs.sensor_id','=','sensors.sensor_id')
//        ->rightJoin('sensortriggers','sensortriggers.sensor_id','=','sensors.sensor_id')
//        ->select('sensors.id', 'sensors.sensor_id','sensors.location_code','sensors.additional_location_info','sensors.location_coordinates','sensortriggers.level_emergency as emergency_level','sensortriggers.level_warning as warning_level','sensorlogs.stream_height')
//        ->select('sensors.id', 'sensors.sensor_id','sensortriggers.level_emergency as emergency_level','sensortriggers.level_warning as warning_level','sensorlogs.stream_height')
        ->select('id', 'sensor_id','sensorlogs.stream_height')
        ->orderBy('sensorlogs.timestamp','DESC')
        ->groupBy('sensor_id')
//        ->max('sensorlogs.timestamp');
        ->get();
    var_dump($sensors); die();
    return view('sensorsMap',['sensors' => $sensors]);
});

Route::get('/sensorsLog20', function () {
    $sensor_id = Input::get('sensor_id');
    $sensorlogs = DB::table('sensorlogs')->where('sensor_id','=',$sensor_id)->orderBy('timestamp','desc')->limit(24)->get();
    return view('sensorsLogReport',['sensorlogs' => $sensorlogs, 'reportPage' => '1', 'sensorId' => $sensor_id]);
});
//Route::post('api/v1/add-category', function(Request $request){
//    \Illuminate\Support\Facades\Log::info($request);
//});

Route::get('/sensorsLog1thReadingOf30days', function () {
    $sensor_id = Input::get('sensor_id');
    $sensorlogs = DB::table('sensorlogs')
        ->select(DB::raw("date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, id, sensor_id, stream_height, charging, voltage ,timestamp"))
        ->where('sensor_id','=',$sensor_id)
        ->groupBy('time')
        ->orderBy('timestamp','desc')->limit(30)->get();
    return view('sensorsLogReport',['sensorlogs' => $sensorlogs, 'reportPage' => '2', 'sensorId' => $sensor_id]);
});


/**** Sensor Trigger ***/
// Route::get('/sensortrigger', ['uses' => 'Sensor\SensorTriggerController@sensorTriggerReport'])->middleware('auth');
Route::get('/sensortrigger', ['uses' => 'Sensor\SensorTriggerController@sensorTriggerReport']);
Route::post('/addsensortrigger', ['uses' => 'Sensor\SensorTriggerController@addSensorTrigger']);

/*** Showing call log report for specific activity ID ***/
//web url/calllogActivity?activID=3
Route::get('/calllogActivity', 'CallLogReportController@getCallLogReportPerActivity')->middleware('auth');

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
            ->where(DB::raw("timestampdiff(HOUR, timestamp, NOW())"),'<=','24')
            ->limit(1)
            ->get();
        if(sizeof($sensorlogs)>0)
        {
            $sensorlogsAll[] = $sensorlogs[0];
        }
    }
    return view('sensorsMap',['sensors' => $sensorIds, 'sensors24hrs' => $sensorlogsAll]);
});

/** Sensor Trigger Report data display **/
Route::get('/sensortrigger', ['uses' => 'Sensor\SensorTriggerController@sensorTriggerReport'])->middleware('auth');


// check all districts and communes in UploadSoundFile form
Route::get('/checkall', function()
{
    $pro_id = Input::get('pro_id');
    $districs = DB::table('district')
        ->join('commune','district.DCode','=','commune.DCode')
        ->join('targetphones','targetphones.commune_code','=','commune.CCode')
        ->select(DB::raw('COUNT(phone) as phone'))
//          ->select(DB::raw('COUNT(phone) as phone,commune_code as com'))
//            ->groupBy('commune_code')
        ->where('PCode',$pro_id)->where('district.status',1)->get();
//    var_dump($districs);
    return $districs;
});

Route::get('/checkallTest', function()
{
    $pro_id = Input::get('pro_id');
    $districs = DB::table('district')
        ->join('commune','district.DCode','=','commune.DCode')
        ->join('targetphones','targetphones.commune_code','=','commune.CCode')
//        ->select(DB::raw('COUNT(phone) as phone'))
        ->select(DB::raw('COUNT(phone) as phone,commune_code as com'))
        ->groupBy('commune_code')
        ->where('PCode',$pro_id)->where('district.status',1)->get();
    var_dump($districs);
//    return $districs;
});

/** Sensor Trigger Add, Edit, and Delete **/
/** Add **/
// get all province to be displayed in add modal
Route::post('/getDistricts', ['uses' => 'Sensor\SensorTriggerController@getDistrictPerProvince'])->middleware('auth');
Route::post('/getCommunes', ['uses' => 'Sensor\SensorTriggerController@getCommunesPerDistrict'])->middleware('auth');
Route::post('/addsensortrigger', ['uses' => 'Sensor\SensorTriggerController@addSensorTrigger'])->middleware('auth');
Route::post('/geteditsensortrigger', ['uses' => 'Sensor\SensorTriggerController@getEditSensorTrigger'])->middleware('auth');
Route::post('/saveeditsensortrigger', ['uses' => 'Sensor\SensorTriggerController@saveEditSensorTrigger'])->middleware('auth');
Route::post('/deletesensortrigger', ['uses' => 'Sensor\SensorTriggerController@deleteSensorTrigger'])->middleware('auth');

Route::get('/getAllProvinces', ['uses' => 'Sensor\SensorTriggerController@getAllProvinces'])->middleware('auth');

Route::get('/testAPI', function () {
    $findCommune = '0110203';
    // *** If category->base is NUMERICAL CHARACTERS *** //
    if(preg_match('/^[0-9]/',$findCommune))
    {
        // *** AND If category->base starting with 0 character, THEN cut it out. *** //
        if(substr($findCommune,0,1) === "0")
            $findCommune = substr($findCommune,1);

        // *** AND IF len($findCommune) is between 5 (ex:10205)and 6(ex:120204) *** //
        if(strlen($findCommune)==5 || strlen($findCommune)==6){
            $commune_code = $findCommune;
            echo $commune_code."=> correct commune code; ";
        }
    }
});

Route::get('/sensorlogReportInChart', ['uses' => 'sensorLogChartCtrl@createChart30Days']);

Route::get('/testGetPhoneNumbers', ['uses' => 'Sensor\ReceivingSensorInfoAPIController@getPhoneNumbersToBeCalled']);

Route::post('/getSSCommunes', ['uses' => 'Sensor\SensorsController@getSSCommunesPerDistrict'])->middleware('auth');

Route::post('/getSSList', ['uses' => 'CallLogReportController@getSensorListInSelectedProvince'])->middleware('auth');