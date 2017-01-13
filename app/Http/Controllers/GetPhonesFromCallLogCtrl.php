<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Socialite;
use App\Models\targetphones;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
use Response;
use Session;

class GetPhonesFromCallLogCtrl extends Controller {

    /**
    * Function to select phone contacts under effected communes then make a call
    * @param $request
    * @return array of phone numbers
    */
    public function callThem(Request $request)
    {
		$communes_selected = "";
		foreach ($request->request as $key => $item) {
			$communes_selected = $communes_selected . "," . $item;
		}
		$phoneNumbers = \DB::table('targetphones')->select('phone')->whereIn('commune_code',explode(",",$communes_selected))->count();

		Session::flash('message',$phoneNumbers);
    }

    /**
     * Function to add new contact from RapidPro into ews1294.info
     * this function to be called in WebHook in RapidPro.
     * @return array of phone numbers
     */
	public function registerNewContact()
	{
        $jsonStr = Input::get('values');
        $cateDecode = json_decode($jsonStr);
        foreach($cateDecode as $i => $v)
        {
            $findCommune = $v->category->base;
            // *** If category->base is NUMERICAL CHARACTERS *** //
            if(preg_match('/^[0-9]/',$findCommune))
            {
                // *** AND If category->base starting with 0 character, THEN cut it out. *** //
                if(substr($findCommune,0,1) === "0")
                    $findCommune = substr($findCommune,1);

                // *** AND IF len($findCommune) is between 5 (ex:10205)and 6(ex:120204) *** //
                if(strlen($findCommune)==5 || strlen($findCommune)==6){
                    $commune_code = $findCommune;
                    echo $commune_code."=> correct commune code; ";
                    break;
                }
            }
        }

        $phone = Input::get('phone');
        if($phone != "" && $commune_code != "")
        {
                // INSERT addresses INTO TARGET PHONE TABLE
                $targetphones = new targetphones;
                // Query existing phone in given commune
                $itemExist = $targetphones::select('id')
                                        ->where('phone',$phone)
                                        ->where('commune_code',$commune_code)
                                        ->first();
                if(!isset($itemExist))
                {
                    $targetphones->commune_code = $commune_code;
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
        echo "phone number: ".$phone;
	}

	// currently used
    /**
     * Function to get phone contacts from Verboice to insert into Database
     * @param $request reminder group id (to be selected by user on Form)
     * @return $reminderGroups
     */
	public function importPhoneContactsFromVerboice(Request $request)
	{
        $verboiceCallLogAPI = 'http://verboice-cambodia.instedd.org/api/projects/359/reminder_groups.json?id[]=1';
        $curlCallLog = curl_init($verboiceCallLogAPI);
        curl_setopt($curlCallLog, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curlCallLog, CURLOPT_USERPWD, Config::get('constants.VERBOICE_AUTH_USER').":".Config::get('constants.VERBOICE_AUTH_PASS'));
        curl_setopt($curlCallLog, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlCallLog, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($curlCallLog, CURLOPT_HEADER, 0);
        $callLogResponse = curl_exec($curlCallLog);
        $callLogArray = json_decode($callLogResponse, true);
        $reminderGroups = "";
        $request_split = explode("#;",$request->rg_name);
        $reminderGroups = \DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->where('CCode',$request_split[0])->get();
        foreach ($reminderGroups as $key => $reminderGroup) {
            for ($i = count($callLogArray) - 1; $i >= 0; $i--) {
                 if ($callLogArray[$i]['name'] == $reminderGroup->CReminderGroup) {
                     if ($callLogArray[$i]['name'] == $request_split[1]) {
                         $phone = $callLogArray[$i]['addresses'];
                         // INSERT addresses INTO TARGET PHONE TABLE
                         foreach ($phone as $key => $eachPhone) {
                             $new_eachPhone = preg_replace('/^(\+855|855)/', '0', $eachPhone);
                             $targetphones = new targetphones;
                             $targetphones->commune_code = $request_split[0];
                             $targetphones->phone = $new_eachPhone;
                             $targetphones->save();
                         }
                     }
                 }
            }
        }
		$reminderGroups = \DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
		return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
	}

    /**
     * Function to get all reminder groups from Database
     * @return $reminderGroups to view ReadPhonesFromCallLog
     */
	public function getReminderGroups()
	{
			$reminderGroups = DB::table('commune')->select('CCode','CReminderGroup')->whereNotNull('CReminderGroup')->get();
			return view('ReadPhonesFromCallLog',['reminderGroups' => $reminderGroups]);
	}

}
