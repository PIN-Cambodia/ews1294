<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Socialite;
use App\Models\targetphones;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
// use Request;
use Response;
// use App\Http\Controllers\Session;
use Session;
use Illuminate\Support\Facades\Log;

// http://blog.damirmiladinov.com/laravel/laravel-5.2-socialite-facebook-login.html#.VxQyc5N96fU

class GetPhonesFromCallLogCtrl extends Controller {

	public function callThem(Request $request)
  {
    // dump the given variable and end execution of the script
    //dd($request->request);
		// $communes = array();
		$communes_selected = "";
		foreach ($request->request as $key => $item) {
			// dd($item);
			$communes_selected = $communes_selected . "," . $item;
			//echo "item = ".$item."<br>" ;
		}
		//dd($communes_selected);
		// $input = Request::all();
		// dd($input);
		// $t = "12,10203";
		// $phoneNumbers = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$communes_selected))->get();
		$phoneNumbers = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$communes_selected))->count();

		Session::flash('message',$phoneNumbers);
		// dd($phoneNumbers);
		// return view('uploadSoundFile',['reminderGroups' => $phoneNumbers]);
		// return view('uploadSoundFile',['reminderGroups' => $reminderGroups]);
    // if(Auth::attempt(['name'=> $request->username, 'password' => $request->password]))
    // {
    //   echo "auth check <br>";
    //   // redirect to upload sound file page
    //   // return redirect()->intended('any url link');
    // }
    // else {
    //   echo "else auth check <br>";
    //   // redirct to the back to the login form
		//
    // }
		//
    // //dd($request->username);
    // echo "username= " . $request->username;
    // echo "password= " . $request->password;
    // var_dump($request);
  }

	// public function registerNewContact()
	// {
	// 		$phone = Input::get('phone');
	// 		$commune = Input::get('commune');
	// 		if($phone != "" && $commune != "")
	// 		{
	// 				// INSERT addresses INTO TARGET PHONE TABLE
	// 				$targetphones = new targetphones;
	// 				// Query existing phone in given commune
	// 				// $itemExist = App\Models::where('phone',)
	// 				$itemExist = $targetphones::select('id')
	// 										->where('phone',$phone)
	// 										->where('commune_code',$commune)
	// 										->first();
	// 				//dd($itemExist->id);
	// 				// dd(isset($itemExist));
	// 				if(!isset($itemExist))
	// 				{
	// 					$targetphones->commune_code = $commune;
	// 					$targetphones->phone = $phone;
	// 					$res = $targetphones->save();
	// 					if($res)
	// 							$res_sms = "Successfully inserted";
	// 					else
	// 							$res_sms = "Fail to insert";
	// 				}
	// 				else {
	// 					$res_sms = "This contact in this commune is already exist!";
	// 				}
	//
	//
	//
	// 		}
	// 		else
	// 				$res_sms = "Fail to insert because some avariables are null.";
	// 		echo $res_sms;
	// 		// return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
	// }

    public function registerNewContactTest()
    {
        //var_dump(Input::json());
//			$test = Input::all();


        // Log::info('Object Register: ' . Response::json($test));
        //$jsonStr = Input::get('values');
        //Log::info('Values: ' . Response::json($val_pass));
//        Log::info('Values: ' . $val_pass);
        // Test fix string of json
        $category = '[{"category": {"base": "0102"}, "node": "271a2112-60b6-456c-9ff0-45ea56fb2135", "time": "2016-10-19T02:39:51.394175Z", "text": "1", "rule_value": "1", "value": "1.00000000", "label": "BanteayMeancheyProvince"}, {"category": {"base": "010202"}, "node": "cff53191-db4e-48fe-8399-5d607481955a", "time": "2016-10-19T02:40:04.841054Z", "text": "2", "rule_value": "2", "value": "2.00000000", "label": "MongkolBoreiDistrict"}]';
        echo $category.'<br/>';
        $category_decode = json_decode($category);
        echo $category_decode.'<br/>';
//        foreach($category['category'] as $i => $v)
//        {
////            Log::info('category: ' . $v['category']);
//              echo $v['category'].'<br/>';
//        }
    }

	public function registerNewContact()
	{
			//var_dump(Input::json());
//			$test = Input::all();


			// Log::info('Object Register: ' . Response::json($test));
			$jsonStr = Input::get('category');
			//Log::info('Values: ' . Response::json($val_pass));
        Log::info('Values: ' . $jsonStr);
        // Test fix string of json
//        $jsonStr = '[{"category": {"base": "0102"}, "node": "271a2112-60b6-456c-9ff0-45ea56fb2135", "time": "2016-10-19T02:39:51.394175Z", "text": "1", "rule_value": "1", "value": "1.00000000", "label": "BanteayMeancheyProvince"}, {"category": {"base": "010202"}, "node": "cff53191-db4e-48fe-8399-5d607481955a", "time": "2016-10-19T02:40:04.841054Z", "text": "2", "rule_value": "2", "value": "2.00000000", "label": "MongkolBoreiDistrict"}]';
//        $category = json_decode($jsonStr);
//        foreach($category['category'] as $i => $v)
//        {
//            Log::info('category: ' . $v['category']);
////                echo $v['category'].'<br/>';
//        }


        die();
			$phone = Input::get('phone');
			$commune = "010101";


			if($phone != "")
			{
					// INSERT addresses INTO TARGET PHONE TABLE
					$targetphones = new targetphones;
					// Query existing phone in given commune
					// $itemExist = App\Models::where('phone',)
					$itemExist = $targetphones::select('id')
											->where('phone',$phone)
											->where('commune_code',$commune)
											->first();
					//dd($itemExist->id);
					// dd(isset($itemExist));
					if(!isset($itemExist))
					{
						$targetphones->commune_code = $commune;
						$targetphones->phone = $phone;
						$res = $targetphones->save();
						if($res)
								$res_sms = "Successfully inserted";
						else
								$res_sms = "Fail to insert";
					}
					else {
						$res_sms = "This contact in this commune is already exist!";
					}

			}
			else
					$res_sms = "Fail to insert because some avariables are null.";
			echo $phone;
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
				$reminderGroups = \DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->where('CCode',$request_split[0])->get();

				foreach ($reminderGroups as $key => $reminderGroup) {
					# code...
					//var_dump($reminderGroup->CReminderGroup);
	        for ($i = count($callLogArray) - 1; $i >= 0; $i--) {
	            // if ($callLogArray[$i]['name'] == $reminderGroup->CReminderGroup) {
							if ($callLogArray[$i]['name'] == $request_split[1]) {
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
