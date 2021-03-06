<?php
namespace App\Http\Controllers\Sensor;
use App\Http\Controllers\Controller;
use App\Models\activities;
use App\Models\Sensorlogs;
use App\Models\sensortriggers;
use App\Models\targetphones;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
class ReceivingSensorInfoAPIController extends Controller
{
    public function __construct()
    {
        // Write Log into a user defined log file i.e storage/log/sensor_log.log
        $this->logger = new Logger('my_sensor_log');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/sensor_log.log')),Logger::INFO);
    }
    /**
     * API to automatically get data from sensor and insert into table sensorlogs
     * Check if the stream height reaches warning or emergency level or not
     * If yes then take action such as sending email and call to officers and/or relevant people in the affected communes
     */
    public function sensorAPI()
    {
        /** Insert data into Table Sensor Logs **/
        $return_inserted_val=$this->insertDataIntoSensorLogTable(Input::get('data'));
        /** Write to Log file and Automatically Check data for Automatic Call or/and send Email */
        // Display and write to log whether data is successfully inserted or not
        if (!empty($return_inserted_val))
        {
            // write to log
            $this->logger->addInfo("Successfully inserted data: " . Input::get('data'));
            echo "Successfully inserted data: " . Input::get('data');
            /** Check for automatically call or send E-mail to relevant people **/
            // Note that this is not working as expected, so it's been disabled from Jul 19th 2018 onwards
            //$this->checkForAutomaticCallOrEmailAction($return_inserted_val);

            // Send this data to the new EWS system through the lambda endpoint
            $data = json_decode(Input::get('data'), true);
            $curl = curl_init();

            // Our two first generation sensors are not correctly named, so let's give them proper IDs
            if($data['sensorId'] == '1020301') { //Kampot
              $data['sensorId'] = '0707030201';
            } elseif ($data['sensorId'] == '15040701') { //Pursat
              $data['sensorId'] = '1504020301';
            }

            curl_setopt_array($curl, array(
              CURLOPT_URL => "https://api.iot.ews1294.info/v1/data-point",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "{\"apiKey\": \"wfYGuHWTJ4XscdNVgogeoQtp\", \"source\": \"bridge-".$data['sensorId']."\", \"payload\": ".Input::get('data')."}",
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
              $this->logger->addWarning("Could not send data to new system: " . $err);
            }
        }
        else {

        }
    }
    /**
     * Function to insert sensor data into table sensorlogs
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

            // correct the sensor time in case what we got doesnt make sense
            if(strtotime(str_replace(':0.000Z', '', $sensor_log_tbl->timestamp)) == false) {
              date_default_timezone_set('Asia/Bangkok');
              $sensor_log_tbl->timestamp = date('Y-m-d\TH:i:0.000\Z');
              $this->logger->addWarning('Date submitted by sensor is not valid. Replacing with ' . $sensor_log_tbl->timestamp);
            }

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
        /** if the received streamHeight of sensor is between the defined warning level value
        *      and the emergency level value then
        *   Trigger call and Send mail to relevant officers in the defined list
        **/
        if($current_sensor_value->stream_height >= $sensor_trigger_result->level_warning
            && $current_sensor_value->stream_height < $sensor_trigger_result->level_emergency)
        {
            /** send email to relevant officers (PCDM, NCDM, PIN staff) **/
            $this->sendMailToOfficers($sensor_trigger_result->affected_communes, $sensor_trigger_result->emails_list, "Warning");
            /** automatically call **/
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,"");
            $this->automaticCallToAffectedPeople($sensor_trigger_result->warning_sound_file, $phone_json, "", $current_sensor_value->sensor_id);
        }
        /** if (the sensor received streamHeight >= defined Emergency level value) then
        *   1. Trigger call and send mail to relevant officers in the defined list
        *   2. Trigger call to phone numbers in all affected_communes list
        **/
        elseif($current_sensor_value->stream_height >= $sensor_trigger_result->level_emergency)
        {
            /** send email to relevant officers (PCDM, NCDM, PIN staff) **/
            $this->sendMailToOfficers($sensor_trigger_result->affected_communes, $sensor_trigger_result->emails_list, "Emergency");
            /** automatically call **/
            // list of Officers' and Villagers' phone numbers
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,$sensor_trigger_result->affected_communes);
            $this->automaticCallToAffectedPeople($sensor_trigger_result->emergency_sound_file, $phone_json, $sensor_trigger_result->affected_communes, $current_sensor_value->sensor_id);
        }
    }
    /**
     * function to send email to officers
     * @param $emails_list is list of PCDM and/or NCDM officers that we need to send email to
     * @param $alert_level can be either Warning_level or Emergency_level
     *
     */
    public function sendMailToOfficers($affected_place, $emails_list, $alert_level)
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
        $officer_email =explode(",", $emails_list);
        $subject_title = "EWS1294: The Early Warning System Alert";
        if($alert_level == "Warning")
        {
            $content = "<p>The automated water level gauge located in <font color='#666600'> " . $commune_list ." <b>commune</b>, "
                        . $distric_val . " <b>district</b>, " . $prov_val . " <b>province</b>
                         </font> has detected a potentially dangerous water level reading. As a result a WARNING message has been sent via the EWS1294 mobile phone messaging system to registered users in the affected areas.</p>
                         <p>This mobile phone message directs recipients to take the necessary precautionary measures to respond to this water level alert.</p>
                         <p>As a member of the Early Warning System team, we ask that you carry out any necessary actions to respond to these elevated water level readings as well. You cooperation is greatly appreciated.</p>
                         ";
            $content_kh = "<p>រង្វាស់កម្រិតទឹកដោយស្វ័យប្រវត្តិដែលមានទីតាំងស្ថិតក្នុង <font color='#666600'><b>​ឃុំ </b> " . $commune_list_kh
                            . " <b>ស្រុក </b> " . $distric_val_kh . " <b>ខេត្ត </b>" . $prov_val_kh . "</font> បានរកឲ្យឃើញថា កម្រិតកំពស់ទឹកកើនឡើងដល់ចំនុចគ្រោះថ្នាក់។ ដូច្នេះ លេខទូរស័ព្ទលោកអ្នក ដែលបានចុះឈ្មោះជាមួយនឹងប្រព័ន្ធប្រកាសឲ្យដឹងមុន១២៩៤ នៅតំបន់ដែលរងផលប៉ះពាល់ នឹងទទួលបានសារក្រើនរំលឹកឲ្យប្រុងប្រយ័ត្ន។ </p>
                            <p>សារទូរស័ព្ទដៃនេះនឹងធ្វើឲ្យអ្នកដែលបានទទួលសារ ចាត់វិធានការបង្ការចាំបាច់ទុកជាមុន ដើម្បីឆ្លើយតបទៅនឹងការជូនដំណឹងកម្រិតទឹកនេះ។
                                ក្នុងនាមជាសមាជិកនៃក្រុមប្រព័ន្ធប្រកាសឲ្យដឹងមុនមួយរូប, យើងសុំឱ្យអ្នកអនុវត្តសកម្មភាពដែលចាំបាច់ដើម្បីឆ្លើយតបទៅនឹងកម្រិតទឹក កើនឡើងទាំងនេះ ។ កិច្ចសហប្រតិបត្តិការដែលអ្នកបានធ្វើត្រូវបានកោតសរសើរយ៉ាងខ្លាំង។
                            </p>";
        }
        elseif($alert_level == "Emergency")
        {
            $content = "<p>The automated water level gauge located in  <font color='red'> " . $commune_list ." <b>commune</b>, "
                        . $distric_val . " <b>district</b>, " . $prov_val . " <b>province</b>
                         </font> has detected a potentially dangerous water level reading. As a result an EMERGENCY message has been sent via the EWS1294 mobile phone messaging system to registered users in the affected areas.</p>
                         <p>This mobile phone message directs recipients to take the necessary precautionary measures to respond to this water level alert.
                         </p>
                         <p>As a member of the Early Warning System team, we ask that you carry out any necessary actions to respond to these elevated water level readings as well. You cooperation is greatly appreciated.
                         </p>";
            $content_kh = "បង្គោល​វាស់កម្ពស់​ទឹកជំនន់​ដែល​មាន​ទីតាំង​ស្ថិត​នៅ​ក្នុង <font color='red'><b>​ឃុំ </b> " . $commune_list_kh
                            . " <b>ស្រុក </b> " . $distric_val_kh . " <b>ខេត្ត </b>" . $prov_val_kh . "</font> ដូច្នេះ លេខទូរស័ព្ទលោកអ្នក ដែលបានចុះឈ្មោះជាមួយនឹងប្រព័ន្ធប្រកាសឲ្យដឹងមុន១២៩៤ នៅតំបន់ដែលរងផលប៉ះពាល់ នឹងទទួលបានសារប្រកាសអាសន្ន។
                                សារទូរស័ព្ទដៃនេះនឹងធ្វើឲ្យអ្នកដែលបានទទួលសារ ចាត់វិធានការបង្ការចាំបាច់ទុកជាមុន ដើម្បីឆ្លើយតបទៅនឹងការជូនដំណឹងកម្រិតទឹកនេះ។
                                ក្នុងនាមជាសមាជិកនៃក្រុមប្រព័ន្ធប្រកាសឲ្យដឹងមុនមួយរូប, យើងសុំឱ្យអ្នកអនុវត្តសកម្មភាពដែលចាំបាច់ដើម្បីឆ្លើយតបទៅនឹងកម្រិតទឹក កើនឡើងទាំងនេះ ។ កិច្ចសហប្រតិបត្តិការដែលអ្នកបានធ្វើត្រូវបានកោតសរសើរយ៉ាងខ្លាំង។
                                ";
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
    /**
     * Function for automatically call to affected people in affected communes
     * @param $url_sound
     * @param $phone_tobe_called
     * @param $affected_communes
     * @param $sensor_id
     * @return $response or 0
     */
    public function automaticCallToAffectedPeople($url_sound,$phone_tobe_called, $affected_communes,$sensor_id)
    {
        // Create new activity //
        $activity_created = $this->insertNewActivity(sizeof($phone_tobe_called),$url_sound,$affected_communes,$sensor_id);
        if($activity_created > 0)
        {
            $twillioCallApi = "http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload";
            $data = array(
                "api_token" => "C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h",
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
            return 0;
    }
    /**
     * Function to insert New Activity
     * @param $noOfPhones
     * @param $soundFile
     * @param $affected_commune
     * @param $sensor
     * @return activity id
     */
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
    /**
     * Function to get phone numbers
     * @param $officerPhones
     * @param $affectedCommunes
     * @return json string
     */
    public function getPhoneNumbersToBeCalled($officerPhones,$affectedCommunes)
    {
        $targetphones_tbl = new targetphones;
        $phoneNumbersInCommunes = $targetphones_tbl->select('phone')->whereIn('commune_code',explode(",",$affectedCommunes))->get();
        $splitArray = explode(",",$officerPhones);
        foreach ($splitArray as $splitArrayEach)
        {
            $phoneNumbersInCommunes->push(['phone'=> $splitArrayEach]);
        }
        $jsonstr = json_encode($phoneNumbersInCommunes);
        return $jsonstr;
    }
}
