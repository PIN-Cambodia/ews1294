<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Session;
/* Calling user model to be used */
use App\User;

use App\Role;
use App\Permission;
use App\Model\Activities;

use App\Http\Requests;
use Response;

use Illuminate\Http\Request;

class ReceivingCallLogAPIController extends Controller
{
  public function callLogAPI($json_data)
  {
    /* json_decode($json_string, true)
    * When TRUE, returned objects will be converted into associative arrays.
    */
    $data = json_decode($json_data, true);
    foreach ($data as $data_key => $data_value)
    {
      echo "key= " . $data_key . "; value= " . $data_value . "<br />";
    }

  }

}
