<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Socialite;
use App\Models\targetphones;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
// use Illuminate\Http\Request;
use Response;

// http://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.VxQyc5N96fU

class GetPhonesFromCallLogCtrl extends Controller {

	public function registerNewContact()
	{
			$phone = Input::get('phone');
			$commune = Input::get('commune');
			if($phone != "" && $commune != "")
			{
					// INSERT addresses INTO TARGET PHONE TABLE
					$targetphones = new targetphones;
					$targetphones->commune_code = $commune;
					$targetphones->phone = $phone;
					$res = $targetphones->save();
					if($res)
							$res_sms = "Successfully inserted";
					else
							$res_sms = "inserted fail";

			}
			else
					$res_sms = "Fail to insert because some avariables are null.";
			echo $res_sms;
			// return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
	}

	public function getPhoneCallLog(Request $request)
	{
				// dd($request);
				// $verboiceCallLogAPI = 'http://verboice-cambodia.instedd.org/api/call_logs.json?name=' . $id;
				$verboiceCallLogAPI = 'http://verboice-cambodia.instedd.org/api/projects/359/reminder_groups.json?id[]=1';
        $curlCallLog = curl_init($verboiceCallLogAPI);
				//$curlCallLog = curl_init();
        curl_setopt($curlCallLog, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlCallLog, CURLOPT_USERPWD, Config::get('constants.VERBOICE_AUTH_USER').":".Config::get('constants.VERBOICE_AUTH_PASS'));
        curl_setopt($curlCallLog, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlCallLog, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curlCallLog, CURLOPT_HEADER, 0);
        $callLogResponse = curl_exec($curlCallLog);
        $callLogArray = json_decode($callLogResponse, true);
        // Find call log record with $callId
				$reminderGroups = "";
				$request_split = explode("#;",$request->rg_name);
				//var_dump($request_split);
				$reminderGroups = \DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
				foreach ($reminderGroups as $key => $reminderGroup) {
					# code...

	        for ($i = count($callLogArray) - 1; $i >= 0; $i--) {
	            if ($callLogArray[$i]['name'] == $reminderGroup) {
	                // $result = json_encode($callLogArray[$i]);
									// $result = json_encode($callLogArray[$i]['addresses']);
									$phone = $callLogArray[$i]['addresses'];
									// INSERT addresses INTO TARGET PHONE TABLE
									foreach ($phone as $key => $eachPhone) {
											$targetphones = new targetphones;
											$targetphones->commune_code = $request_split[0];
											$targetphones->phone = $eachPhone;
											$targetphones->save();
									}
	            }
	        }
			}
				// var_dump($reminderGroups);
				// $reminderGroupsResult = Response::json($reminderGroups);
		//return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroupsResult]);
		$reminderGroups = \DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
		return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
		// return view('ReadPhonesFromCallLog');
	}

	public function getPhones()
	{
				$rg_name = Input::get('rg_name');
				// $verboiceCallLogAPI = 'http://verboice-cambodia.instedd.org/api/call_logs.json?name=' . $id;
				$verboiceCallLogAPI = 'http://verboice-cambodia.instedd.org/api/projects/359/reminder_groups.json?id[]=1';
        $curlCallLog = curl_init($verboiceCallLogAPI);
				//$curlCallLog = curl_init();
        curl_setopt($curlCallLog, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlCallLog, CURLOPT_USERPWD, Config::get('constants.VERBOICE_AUTH_USER').":".Config::get('constants.VERBOICE_AUTH_PASS'));
        curl_setopt($curlCallLog, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlCallLog, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curlCallLog, CURLOPT_HEADER, 0);
        $callLogResponse = curl_exec($curlCallLog);
        $callLogArray = json_decode($callLogResponse, true);
        // Find call log record with $callId
				$reminderGroups = "";
        for ($i = count($callLogArray) - 1; $i >= 0; $i--) {
            if ($callLogArray[$i]['name'] == $rg_name) {
                // $result = json_encode($callLogArray[$i]);
								// $result = json_encode($callLogArray[$i]['addresses']);
								$reminderGroups = $callLogArray[$i]['addresses'];
								// INSERT addresses INTO TARGET PHONE TABLE

            }
        }
				var_dump($reminderGroups);
				// $reminderGroupsResult = Response::json($reminderGroups);
				$reminderGroupsResult = $reminderGroups;
				$messageReturn = "Success";
		//return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroupsResult]);
		return view('ReadPhonesFromCallLog',['$messageReturn' => $messageReturn]);
		// return view('ReadPhonesFromCallLog');
	}

	public function getReminderGroups()
	{
			$reminderGroups = DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
			return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
	}

}
