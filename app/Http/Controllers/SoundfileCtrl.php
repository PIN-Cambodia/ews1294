<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Socialite;

use App\Models\province;
use Auth;
use Redirect;

// http://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.VxQyc5N96fU

class SoundfileCtrl extends Controller {

	public functin getProvinces()
	{
		$provinces = DB::table('province')->select('PROVINCE_KH')->get();
		return view('uploadSoundFile',['provinces' => $provinces]);
	}

}
