<?php

namespace App\Http\Controllers\Sensor;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sensorlogs;
use Illuminate\Support\Facades\Input;

class ReceivingSensorInfoAPIController extends Controller
{

    public function sensorAPI()
    {
        // Insert data into Table Sensor Logs
        echo $this->insertDataIntoSensorLogTable(Input::get('data'));
    }

    public function insertDataIntoSensorLogTable($inputData)
    {
        /* json_decode($json_string, true)
        * When TRUE, returned objects will be converted into associative arrays.
        */
        $parsing_json_data = json_decode($inputData, true);
        $sensor_log_tbl = new Sensorlogs;
        $sensor_log_tbl -> sensor_id  = $parsing_json_data['sensorId'];
        $sensor_log_tbl -> stream_height = $parsing_json_data['streamHeight'];
        $sensor_log_tbl -> charging = $parsing_json_data['charging'];
        $sensor_log_tbl -> voltage = $parsing_json_data['voltage'];
        $sensor_log_tbl -> timestamp = $parsing_json_data['timestamp'];
        $success = $sensor_log_tbl -> save();
        //echo "suc= " . $success;
        if(!empty($success)) return "Success: " . Input::get('data');
        else return "Error: Data cannot be inserted.";

    }
}
