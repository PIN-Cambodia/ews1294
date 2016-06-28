<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class UserauthController extends Controller
{
  public function loginauth(Request $request)
  {
    // dump the given variable and end execution of the script
    //dd($request->_token);
    dd($request);
    //var_dump($request);
  }

  public function register(Request $request)
  {
    // dump the given variable and end execution of the script
    //dd($request->_token);
    dd($request);
    //var_dump($request);
  }
}
