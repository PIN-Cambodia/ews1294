<?php

namespace App\Http\Controllers;
use App\Models\province;
use App\Models\targetphones;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;

class Registationcontroller extends Controller
{

		public function getAllProvinces()
    {
        $provinces = DB::table('province')->get();
        return view('add_new_users', ['provinces' => $provinces]);
    }
    public function postPhoneNumber(Request $requests){
    	$this->validate($requests,[
    		'phone'=>'required|unique:targetphones|min:7',
    		'province'=>'required']);
    	$insert = new targetphones;
    	$insert->phone = $requests->input('phone');
    	$insert->commune_code =$requests->input('percommune');
    	$is_inserted = $insert->save();
    	if($is_inserted){
    		echo "successful register";
    	}
    	else{
    		\Session::flash('message_error','fail register');
    		
    	}
    	

    }


    

}
