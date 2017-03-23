<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


Class ContactController extends Controller
{
         public  function getContact(){
        return view('contactUs');

    }
 
}
