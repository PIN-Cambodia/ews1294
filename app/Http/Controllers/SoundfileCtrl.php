<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Socialite;

use App\Models\activities;
use Auth;
use Redirect;
use App\Role;
use App\Permission;
use Illuminate\Support\Facades\Input;
use Response;


// http://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.VxQyc5N96fU

class SoundfileCtrl extends Controller {

	public function getProvinces()
	{
			$provinces = DB::table('province')->select('PROVINCE_KH')->get();
			return view('uploadSoundFile',['provinces' => $provinces]);
	}

	public function insertNewActivity()
	{
			$communes = Input::get('communes');
			$noOfPhones = Input::get('noOfPhones');
			// dd(Auth::user()->id);
			// $activitie = province::all();
			// dd($activitie);
			$activities = new activities;
			$activities->manual_auto = 1;
			$activities->user_id = Auth::user()->id;
			$activities->list_commune_codes = $communes;
			$activities->no_of_phones_called = $noOfPhones;
			$activities->save();

			return $activities->id;
	}

}
