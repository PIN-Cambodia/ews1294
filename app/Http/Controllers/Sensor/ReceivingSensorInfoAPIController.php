<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\activities;
use App\Models\Sensorlogs;
use App\Models\sensortriggers;
use App\Models\targetphones;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Illuminate\Support\Facades\Config;

class ReceivingSensorInfoAPIController extends Controller
{
    public function __construct()
    {
        // Write Log into a user defined log file i.e storage/log/sensor_log.log
        $this->logger = new Logger('my_sensor_log');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/sensor_log.log')),Logger::INFO);
    }
    public function sensorAPI()
    {
        /** Insert data into Table Sensor Logs **/
        $return_inserted_val=$this->insertDataIntoSensorLogTable(Input::get('data'));

        /** Write to Log file and Automatically Check data for Automatic Call or/and send Email */
        // Display and write to log whether data is successfully inserted or not
        if (!empty($return_inserted_val))
        {
            // write to log
            $this->logger->addInfo(nl2br("Successfully inserted data: " . Input::get('data')));
            echo "Successfully inserted data: " . Input::get('data');

            /** Check for automatically call or send E-mail to relevant people **/
             $this->checkForAutomaticCallOrEmailAction($return_inserted_val);
        }
    }
    /**
     * Function to insert sensor data into tbl sensorlogs
     * @param $inputData
     * @return mixed
     */
    public function insertDataIntoSensorLogTable($inputData)
    {
        /* json_decode($json_string, true): When TRUE, returned objects will be converted into associative arrays. */
        $parsing_json_data = json_decode($inputData, true);
        try {
            $sensor_log_tbl = new Sensorlogs;
            $sensor_log_tbl->sensor_id = $parsing_json_data['sensorId'];
            $sensor_log_tbl->stream_height = $parsing_json_data['streamHeight'];
            $sensor_log_tbl->charging = $parsing_json_data['charging'];
            $sensor_log_tbl->voltage = $parsing_json_data['voltage'];
            $sensor_log_tbl->timestamp = $parsing_json_data['timestamp'];
            $sensor_log_tbl->save();
            if(!empty($sensor_log_tbl->id)) {
                // return an inserted record in DB
                return $sensor_log_tbl;
            }
        }
        catch (\Exception $e) {
            $this->logger->addError("Input data: " . $inputData
                . " Error: " . $e->getMessage()
                . " in " . $e->getFile());
        }
    }

    /**
    * Function to insert sensor data into tbl sensorlogs
    */
    public function checkForAutomaticCallOrEmailAction($current_sensor_value)
    {
        // create object from table sensortriggers
        $sensor_trigger = new sensortriggers();
        // get data from table sensortriggers (sensor_id,level_warning,level_emergency...etc)
        $sensor_trigger_result = $sensor_trigger->where('sensor_id',$current_sensor_value->sensor_id)->first();
        // dd($sensor_trigger_result);

        /** if the received streamHeight of sensor is between the defined warning level value
        *      and the emergency level value then
        *   Trigger call and Send mail to relevant officers in the defined list
        **/

        if($current_sensor_value->stream_height >= $sensor_trigger_result->level_warning
            && $current_sensor_value->stream_height < $sensor_trigger_result->level_emergency)
        {
            /** send email to relevant officers (PCDM, NCDM, PIN staff) **/
//            phyrum $this->sendMailToOfficers($sensor_trigger_result->affected_communes, $sensor_trigger_result->emails_list, "Warning");

            /** automatically call **/
            // https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/
            $url_sound_file_warning = "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/".$sensor_trigger_result->warning_sound_file;
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,"");
            //echo $phone_json;
            $this->automaticCallToAffectedPeople($sensor_trigger_result->warning_sound_file, $phone_json, "", $current_sensor_value->sensor_id);
        }

        /** if (the sensor received streamHeight >= defined Emergency level value) then
        *   1. Trigger call and send mail to relevant officers in the defined list
        *   2. Trigger call to phone numbers in all affected_communes list
        **/
        elseif($current_sensor_value->stream_height >= $sensor_trigger_result->level_emergency)
        {
            /** send email to relevant officers (PCDM, NCDM, PIN staff) **/
//            phyrum $this->sendMailToOfficers($sensor_trigger_result->affected_communes, $sensor_trigger_result->emails_list, "Emergency");

            /** automatically call **/

            $url_sound_file_emergency = "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/".$sensor_trigger_result->emergency_sound_file;
            // list of Officers' and Villagers' phone numbers
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,$sensor_trigger_result->affected_communes);
            $this->automaticCallToAffectedPeople($sensor_trigger_result->emergency_sound_file, $phone_json, $sensor_trigger_result->affected_communes, $current_sensor_value->sensor_id);
        }

    }

    /**
     * @param $email_lists is list of PCDM and/or NCDM officers that we need to send email to
     * @param $alert_level can be either Warning_level or Emergency_level
     *
     */
    public function sendMailToOfficers($affected_place, $email_lists, $alert_level)
    {
        /** get location of affected place */
        $affected_place_arr = explode(",", $affected_place);
        // for province code from 1-> 9
        if(strlen($affected_place_arr[0]) == 5)
        {
            $province_str_len = 1;
            $district_str_len = 3;
        }
        // for province code from 10 upward
        elseif(strlen($affected_place_arr[0]) == 6)
        {
            $province_str_len = 2;
            $district_str_len = 4;
        }
        $province_code = substr($affected_place_arr[0],0,$province_str_len);
        $district_code = substr($affected_place_arr[0],0,$district_str_len);
        // province
        if(!empty($province_code))
        {
            $provin_query = DB::table('province')->where('PROCODE', $province_code)->get();
            if(!empty($provin_query))
            {
                $prov_val_kh = $provin_query[0]->PROVINCE_KH;
                $prov_val = $provin_query[0]->PROVINCE;
            }
        }
        // district
        if(!empty($district_code)) {
            $distric_query = DB::table('district')->where('DCode', $district_code)->get();
            if (!empty($distric_query)) {
                $distric_val_kh = $distric_query[0]->DName_kh;
                $distric_val = $distric_query[0]->DName_en;
            }
        }
        // communes
        $commune_list=""; $and_clause ="";$commune_list_kh ="";$end_in_kh="";$and_clause_kh="";
        $affected_place_list = DB::table('commune')->whereIn('CCode', $affected_place_arr)->get();
        if(!empty($affected_place_list))
        {
            foreach($affected_place_list as $affected_commune )
            {
                if ($affected_commune === end($affected_place_list))
                {
                    $end_with_comma = "";
                    if(count($affected_place_arr)>1)
                    {
                        $and_clause_kh = " និង ";
                        $and_clause = " and ";
                    }
                }
                else
                {
                    $end_with_comma = ", ";
                    $end_in_kh = " ";
                }

                $commune_list_kh .= $and_clause_kh . $affected_commune->CName_kh . $end_in_kh;
                $commune_list .= $and_clause . $affected_commune->CName_en . $end_with_comma;
            }
        }

        // list of emails of relevant officers
        $officer_email =explode(",", $email_lists);
        $subject_title = "EWS1294: The Early Warning System Alert";

        if($alert_level == "Warning")
        {
            $content = "Our flood detection unit located in <font color='#666600'> " . $commune_list ." <b>commune</b>, "
                        . $distric_val . " <b>district</b>, " . $prov_val . " <b>province</b> 
                         </font> has detected a warning elevation in water levels. 
                         As a result an WARNING message has been sent via our mobile phone messaging 
                         system to registered EWS1294 users in the surrounding areas. 
                         This messages asks the users to take precautionary measures during this 
                         time in order to protect their families and their livelihoods.";
            $content_kh = "បង្គោល​វាស់កម្ពស់​ទឹកជំនន់​ដែល​មាន​ទីតាំង​ស្ថិត​នៅ​ក្នុង <font color='#666600'><b>​ឃុំ </b> " . $commune_list_kh
                            . " <b>ស្រុក </b> " . $distric_val_kh . " <b>ខេត្ត </b>" . $prov_val_kh . "</font> បាន​ឡើង​ដល់កម្រិត​ប្រុងប្រយ័ត្ន។ 
                            ដូច្នេះ​យើង​នឹង​ផ្ញើ​សារបង្ការ​ទុក​ជា​មុន​​​ទៅ​តាម​រយៈ​ប្រព័ន្ធ​ផ្ញើ​សារ​ទៅ​កាន់​ទូរស័ព្ទ​ដៃ​របស់​អ្នក​នៅ​ពេល​ដែល​អ្នក​បាន​ចុះឈ្មោះ​ប្រើប្រាស់ 
                            EWS1294 អំពី​ព័ត៌មាន​នៅ​ជុំវិញ​តំបន់​របស់​អ្នក​។ សារ​នេះ​នឹង​ប្រាប់​ឲ្យ​អ្នក​ចាត់​វិធានការ​ត្រៀម​បង្ការ​ទុក​ជា​មុន​​ដើម្បី​ការពារ​គ្រួសារនិង​ទ្រព្យ​សម្បត្តិ​របស់​អ្នក​។";
        }
        elseif($alert_level == "Emergency")
        {
            $content = "Our flood detection unit located in <font color='red'> " . $commune_list ." <b>commune</b>, "
                        . $distric_val . " <b>district</b>, " . $prov_val . " <b>province</b> 
                         </font> has detected another dangerous elevation in water levels. 
                         As a result an EMERGENCY message has been sent via our mobile phone messaging system 
                         to registered EWS1294 users in the surrounding areas. 
                         This messages asks the users to take evacuation measures during this time 
                         in order to protect their families and their livelihoods.";
            $content_kh = "បង្គោល​វាស់កម្ពស់​ទឹកជំនន់​ដែល​មាន​ទីតាំង​ស្ថិត​នៅ​ក្នុង <font color='red'><b>​ឃុំ </b> " . $commune_list_kh
                            . " <b>ស្រុក </b> " . $distric_val_kh . " <b>ខេត្ត </b>" . $prov_val_kh . "</font> បាន​ឡើង​ដល់កម្រិត​ប្រកាស​អាសន្ន។ 
                            ដូច្នេះ​យើង​នឹង​ផ្ញើ​សារ​ប្រកាស​អាសន្ន​ទៅ​តាម​រយៈ​ប្រព័ន្ធ​ផ្ញើ​សារ​ទៅ​កាន់​ទូរស័ព្ទ​ដៃ​របស់​អ្នក​នៅ​ពេល​ដែល​អ្នក​បាន​ចុះឈ្មោះ​ប្រើប្រាស់ 
                            EWS1294 អំពី​ព័ត៌មាន​នៅ​ជុំវិញ​តំបន់​របស់​អ្នក​។ សារ​នេះ​នឹង​ប្រាប់​ឲ្យ​អ្នក​ចាត់​វិធានការ​ជម្លៀស​ចេញ​ដើម្បី​ការពារ​គ្រួសារនិង​ទ្រព្យ​សម្បត្តិ​របស់​អ្នក​។";
        }

        // send email to every relevant officers in the list
        foreach ($officer_email as $officer_email)
        {
            try{
                Mail::send('emails.sensoremail',
                    [ 'content'=>$content, 'content_kh'=>$content_kh],
                    function($message) use ($officer_email, $subject_title, $alert_level) {
                        $message ->to($officer_email)
                            ->subject($subject_title);
                        // return message of sending email
                        echo "<br>" . $alert_level . " Alert Email is sent to <b> " . $officer_email . "</b> ";
                    });
                $this->logger->addInfo(nl2br("Successfully Sending " . $alert_level . " Alert Email to: " . $officer_email));
            }
            catch (\Exception $e) {
                $this->logger->addError("E-mail Sending Error: " . $e->getMessage() . " in " . $e->getFile());
            }
        }
    }

    public function automaticCallToAffectedPeople($url_sound,$phone_tobe_called, $affected_communes,$sensor_id)
    {
        // Create new activity //
        $activity_created = $this->insertNewActivity(sizeof($phone_tobe_called),$url_sound,$affected_communes,$sensor_id);
        echo "start calling<br>";
        echo ($phone_tobe_called);
//        [{"phone":"017696365"}]
        if($activity_created > 0)
        {
            $twillioCallApi = "http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload";
            $data = array(
                "api_token" => "C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h",
//                "contacts" => "[{\"phone\":\"017696365\"}]",
                "contacts" => $phone_tobe_called,
                "activity_id" => $activity_created,
                "sound_url" => "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/".$url_sound,
                "no_of_retry" => "3",
                "retry_time" => "10"
            );

            // Using laravel php library GuzzleHttp for execute external API(Eg: Bong Pheak API)
            $client = new Client();
            $response = $client->request('POST', $twillioCallApi, ['json' => $data]);
            return $response;
        }
        else
        {
            echo "error";
            return 0;
        }
    }

    // Function to insert New Activity //
    public function insertNewActivity($noOfPhones,$soundFile,$affected_commune,$sensor)
    {
        $activities = new activities;
        $activities->manual_auto = 2;
        $activities->user_id = 3;
        $activities->sensor_id = $sensor;
        $activities->list_commune_codes = $affected_commune;
        $activities->no_of_phones_called = $noOfPhones;
        $activities->sound_file = $soundFile;
        $activities->save();

        return $activities->id;
    }

    public function getPhoneNumbersToBeCalled($officerPhones,$affectedCommunes)
    {
        $targetphones_tbl = new targetphones;
        $phoneNumbersInCommunes = $targetphones_tbl->select('phone')->whereIn('commune_code',explode(",",$affectedCommunes))->get();

        $splitArray = explode(",",$officerPhones);
        foreach ($splitArray as $splitArrayEach)
        {
            $phoneNumbersInCommunes->push(['phone'=> $splitArrayEach]);
        }
        return Response::json($phoneNumbersInCommunes);
    }
}
