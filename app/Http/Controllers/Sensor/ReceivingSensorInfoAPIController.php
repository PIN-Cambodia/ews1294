<?php

namespace App\Http\Controllers\Sensor;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Sensorlogs;
use Illuminate\Support\Facades\Input;
use League\Flysystem\Exception;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class ReceivingSensorInfoAPIController extends Controller
{
    public function __construct()
    {
        $this->logger = new Logger('my_sensor_log');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/sensor_log.log')),Logger::INFO);

    }
    public function sensorAPI()
    {
        // Write Log into a specific defined log file
        //$logger = new Logger('my_sensor_log');
        //$logger->pushHandler(new StreamHandler(storage_path('logs/sensor_log.log')),Logger::INFO);

        // Insert data into Table Sensor Logs
        $return_val=$this->insertDataIntoSensorLogTable(Input::get('data'));

        //dd($return_val);
        // Display and write to log whether data is successfully inserted or not
        if (!empty($return_val))
        {
            // write to log
            $this->logger->addInfo("successfully inserted: " . Input::get('data'));
            echo "Success: " . Input::get('data');
        }
        else
        {
            // write to log
            $this->logger->addError("Error while inserted: " . Input::get('data'));
            echo "Error: Data cannot be inserted.";
        }

        // Check if the inserted data has the streamHeight reached warning or emergency level


    }
    /*
    * Function to insert sensor data into tbl sensorlogs
    */
    public function insertDataIntoSensorLogTable($inputData)
    {
        /*
        * json_decode($json_string, true)
        * When TRUE, returned objects will be converted into associative arrays.
        */

        $parsing_json_data = json_decode($inputData, true);
        $sensor_log_tbl = new Sensorlogs;
        try
        {
            $sensor_log_tbl->sensor_id = $parsing_json_data['sensorId'];
            $sensor_log_tbl->stream_height = $parsing_json_data['streamHeight'];
            $sensor_log_tbl->charging = $parsing_json_data['charging'];
            $sensor_log_tbl->voltage = $parsing_json_data['voltage'];
            $sensor_log_tbl->timestamp = $parsing_json_data['timestamp'];
            $sensor_log_tbl->save();
        }
        catch (Exception $e)
        {
            $this->logger->addError("Error while inserted: " . $inputData . "/n " . $e);
        }


        if(!empty($sensor_log_tbl->id))
        {
            // return Response::json(array('success' => true, 'last_insert_id' => $data->id), 200);
            return $sensor_log_tbl->id;
        }
        else
        {
            $this->logger->addError("Error while inserted: " . $inputData);
        }

        // return $success;
    }
    /*
    * Function to insert sensor data into tbl sensorlogs
    */
    public function checkDataForCallAction($inputData)
    {

    }

}
