<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Session;
/* Calling user model to be used */
use App\User;

use App\Role;
use App\Permission;
use App\Models\Calllogs;

use App\Http\Requests;
use Response;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class ReceivingCallLogAPIController extends Controller
{
  public function callLogAPI()
  {
    /* json_decode($json_string, true)
    * When TRUE, returned objects will be converted into associative arrays.
    */
    $parsing_json_data = json_decode(Input::get('clog'), true);
    $call_log = new Calllogs;
    $call_log -> phone_number = $parsing_json_data['phone'];
    $call_log -> result = $parsing_json_data['status'];
    $call_log -> duration = $parsing_json_data['duration'];
    $call_log -> called_time = $parsing_json_data['date'] . " " . $parsing_json_data['time'] ;
    $call_log -> no_of_retries = $parsing_json_data['retries'];
    $call_log -> project_id = $parsing_json_data['project_id'];
    $call_log -> call_flow_id = $parsing_json_data['call_flow_id'];
    $call_log -> retry_time = $parsing_json_data['retry_time'];
    $call_log -> max_retry = $parsing_json_data['max_retry'];
    $call_log -> activity_id = $parsing_json_data['activity_id'];
    $success = $call_log -> save();
    if(!empty($success)){ return "Success: " . Input::get('clog');}
    else { return "Error: Data cannot be inserted.";}
  }
}
