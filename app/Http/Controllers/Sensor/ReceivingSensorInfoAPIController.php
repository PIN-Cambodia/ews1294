<?php

namespace App\Http\Controllers\Sensor;

use App\Http\Controllers\Controller;
use App\Models\Sensorlogs;
use App\Models\sensortriggers;
use App\Models\targetphones;
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
        // Insert data into Table Sensor Logs
        $return_inserted_val=$this->insertDataIntoSensorLogTable(Input::get('data'));
        // Display and write to log whether data is successfully inserted or not
        if (!empty($return_inserted_val))
        {
            // write to log
            $this->logger->addInfo(nl2br("Successfully inserted data: " . Input::get('data')));
            echo "Successfully inserted data: " . Input::get('data');
            // Check for automatically call or send E-mail to relevant people
             //$this->checkForAutomaticCallOrEmailAction($return_inserted_val);
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
        $sensor_trigger_result = $sensor_trigger->where('sensor_id',$current_sensor_value->sensor_id)
                                    ->first();

        /** if the received streamHeight of sensor is between the defined warning level value
        *      and the emergency level value then
        *   Trigger call and Send mail to relevant officers in the defined list
        **/

        if($current_sensor_value->stream_height >= $sensor_trigger_result->level_warning
            && $current_sensor_value->stream_height < $sensor_trigger_result->level_emergency)
        {
           // echo "<br/> Warning level is reached <br/>" . asset('js/ajax-district.js');

            // send email to relevant officers (PCDM, NCDM)
            //phyrum $this->sendMailToOfficers($sensor_trigger_result->emails_list, "Warning");


            // https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/
            $url_sound_file_warning = "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/".$sensor_trigger_result->warning_sound_file;
            /*$officer_phones = Response::json($sensor_trigger_result->phone_numbers);
            $splitOfficerPhones = explode(",",$sensor_trigger_result->phone_numbers);
            foreach ($splitOfficerPhones as $eachOfficerPhone)
            {
                $jsonStr = '{"phone":"'.$eachOfficerPhone.'"}';
                $phoneNumbersInCommunes->push($jsonStr);
            }
            return Response::json($phoneNumbersInCommunes);*/
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,"");
            //echo $phone_json;
            echo $this->automaticCallToAffectedPeople($url_sound_file_warning, $phone_json);

        }


        /** if (the sensor received streamHeight >= defined Emergency level value) then
        *   1. Trigger call and send mail to relevant officers in the defined list
        *   2. Trigger call to phone numbers in all affected_communes list
        **/
        elseif($current_sensor_value->stream_height >= $sensor_trigger_result->level_emergency)
        {
            // echo "<br/> Emergency level is reached <br/>";
            // send email to relevant officers (PCDM, NCDM)
            //phyrum $this->sendMailToOfficers($sensor_trigger_result->emails_list, "Emergency");
            // list of Officers' and Villagers' phone numbers
            $url_sound_file_emergency = "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/".$sensor_trigger_result->emergency_sound_file;
            // get villlagers' phone numbers
            $phone_json = $this->getPhoneNumbersToBeCalled($sensor_trigger_result->phone_numbers,$sensor_trigger_result->affected_communes);

            echo $this->automaticCallToAffectedPeople($url_sound_file_emergency, $phone_json);

        }


    }

    /**
     * @param $email_lists is list of PCDM and/or NCDM officers that we need to send email to
     * @param $alert_level can be either Warning_level or Emergency_level
     *
     */
    public function sendMailToOfficers($email_lists, $alert_level)
    {
        // list of emails of relevant officers
        $officer_email =explode(",", $email_lists);

        if($alert_level == "Warning")
        {
            $title = "Warning Alert from Sensor";
            $content = "This is Warning Alert Notification";
        }
        if($alert_level == "Emergency")
        {
            $title = "Emergency Alert from Sensor";
            $content = "This is Emergency Alert Notification";
        }

        // send email to every relevant officers in the list
        foreach ($officer_email as $officer_email)
        {
            Mail::send('emails.sensoremail',
                ['title'=>$title, 'content'=>$content],
                function($message) use ($officer_email, $title, $alert_level) {
                    $message ->to($officer_email)
                        ->subject($title)
                        ->replyTo('noreply@ews1294.info');

                    // return message of sending email
                    echo "<br><h4> Email sent </h4>" . $alert_level . " Alert Email is sent to <b> " . $officer_email . "</b> ";

                });
        }
    }

    /**
     * @param $phone_numbers
     */
    public function automaticCallToAffectedPeople($url_sound,$phone_tobe_called)
    {

        /*$data = array("api_token" => Config::get('constants.TWILIO_API_TOKEN'), "clog" => json_encode($callLogRecord));
        $data_string = json_encode($data);
        $ch = curl_init('http://ews-dashboard-production.ap-southeast-1.elasticbeanstalk.com/api/v1/receivingcalllog');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        return;*/

        echo "start calling<br>";
        $twillioCallAPI = 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload';
        $fields = array(
            "api_token" => "C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h",
            "contacts" => $phone_tobe_called,
            "activity_id" => "999",
            "sound_url" => $url_sound,
            "no_of_retry" => "3",
            "retry_time" => "10"
        );
        $data_fields = json_encode($fields);
        $curltwillioCallAPI = curl_init($twillioCallAPI);
        curl_setopt($curltwillioCallAPI, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curltwillioCallAPI, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curltwillioCallAPI, CURLOPT_POSTFIELDS, $data_fields);
        curl_setopt($curltwillioCallAPI, CURLOPT_POSTFIELDS, $data_fields);
        curl_setopt($curltwillioCallAPI, CURLOPT_HEADER,  array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_fields))
        );

        $curlResponse = curl_exec($curltwillioCallAPI);
        return $curlResponse;
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
