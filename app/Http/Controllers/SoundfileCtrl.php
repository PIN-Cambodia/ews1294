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
//use Illuminate\Support\Facades\Storage;

// http://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.VxQyc5N96fU

class SoundfileCtrl extends Controller {

	public function getProvinces()
	{
			$provinces = DB::table('province')->select('PROVINCE_KH')->get();
			return view('uploadSoundFile',['provinces' => $provinces]);
	}

	public function insertNewActivity(Request $request)
	{
        $communes = Input::get('communes');
		$noOfPhones = Input::get('noOfPhones');
        $newfilename='soundFile_'.date('m_d_Y_hia').'.'.$request->file('soundFile')->getClientOriginalExtension();
        $request->file('soundFile')->move(public_path("/sounds"), $newfilename);

        $activities = new activities;
		$activities->manual_auto = 1;
		$activities->user_id = Auth::user()->id;
		$activities->list_commune_codes = $communes;
		$activities->no_of_phones_called = $noOfPhones;
        $activities->sound_file = $newfilename;
		$activities->save();

			return array($activities->id,$newfilename);
	}

}
