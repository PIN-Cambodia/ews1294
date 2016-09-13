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
  //public function callLogAPI($json_data)
  public function callLogAPI()
  {
    /* json_decode($json_string, true)
    * When TRUE, returned objects will be converted into associative arrays.
    */
    $parsing_json_data = json_decode(Input::get('clog'), true);
    $call_log = new Calllogs;
    foreach ($parsing_json_data as $data_key => $data_value)
    {
      $call_log -> $data_key = $data_value;
      $call_log -> save();
    }

  }

}
