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

use Illuminate\Http\Request;

class ReceivingCallLogAPIController extends Controller
{
  public function callLogAPI($json_data)
  {
    dd(($json_data));
    // $segments = explode('/', $json_data);
    // dd($segments);
    // return view('auth/register')->with('message', 'Successful register new user');
  }

}
