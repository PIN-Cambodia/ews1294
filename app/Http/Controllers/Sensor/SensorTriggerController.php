<?php

namespace App\Http\Controllers\Sensor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SensorTriggerController extends Controller
{
    public function sensorTriggerReport()
    {
        return view('sensor/sensortrigger');
    }
}
