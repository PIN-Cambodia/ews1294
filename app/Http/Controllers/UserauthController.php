<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class UserauthController extends Controller
{
  public function index (Request $request)
  {
    // dump the given variable and end execution of the script
    dd($request);
  }
}
