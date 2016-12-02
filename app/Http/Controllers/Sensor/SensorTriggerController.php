<?php

namespace App\Http\Controllers\Sensor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\sensortriggers;
use Illuminate\Support\Facades\Input;

class SensorTriggerController extends Controller
{
    public function sensorTriggerReport()
    {
        $sensor_trigger= sensortriggers::all();
        return view('sensor/sensortrigger', ['sensor_trigger' => $sensor_trigger]);
    }

    public function getPDCDataForSensorTrigger()
    {
        $province = province::all();
        //$district = DB::table('province')->get();
        //$province = DB::table('province')->get();


    }

    public function addSensorTrigger(Request $request)
    {
        var_dump($request);

    }
}
