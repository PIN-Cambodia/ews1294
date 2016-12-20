<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class Inspire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inspire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Display an inspiring quote';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /*$twillioCallAPI = 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload';
        $fields = '[{
            "api_token" => "C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h",
            "contacts" => "[{"phone":"017696365"}]",
            "activity_id" => 999,
            "sound_url" => "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/soundFile_11_25_2016_0518pm.mp3",
            "no_of_retry" => 3,
            "retry_time" => 10
            }]'
        ;
        $data_fields = json_encode($fields);
        echo $data_fields;
        $curltwillioCallAPI = curl_init($twillioCallAPI);
        curl_setopt($curltwillioCallAPI, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curltwillioCallAPI, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curltwillioCallAPI, CURLOPT_POSTFIELDS, $data_fields);
        curl_setopt($curltwillioCallAPI, CURLOPT_HEADER,  array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_fields))
        );

        $curlResponse = curl_exec($curltwillioCallAPI);
        Log::info($curlResponse);
        return;*/
        $twillioCallApi = "http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload";
        $data = array("api_token" => "C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h", "contacts" => "[{\"phone\":\"017696365\"}]", "activity_id" => "999", "sound_url" => "https://s3-ap-southeast-1.amazonaws.com/ews-dashboard-resources/sounds/soundFile_11_25_2016_0518pm.mp3");
        // Using laravel php library GuzzleHttp for execute external API(Eg: Bong Pheak API)
        $client = new Client();
        $response = $client->request('POST', $twillioCallApi, ['json' => $data]);
        Log::info($response->getBody());
        return json_decode($response->getBody());
    }
}
