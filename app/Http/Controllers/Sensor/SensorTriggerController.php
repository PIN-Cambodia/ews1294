<?php

namespace App\Http\Controllers\Sensor;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\sensortriggers;
use Illuminate\Support\Facades\Input;

class SensorTriggerController extends Controller
{
    public function sensorTriggerReport()
    {
        $sensor_trigger= sensortriggers::all();
        return view('sensor/sensortrigger', ['sensor_trigger' => $sensor_trigger]);
    }

    public function addSensorTrigger(Request $request)
    {
        // dd(Input::all());
        // dd($request->file('warning_sound_file'));

        //dd($request->hasFile('warning_sound_file'));
        //dd(\Illuminate\Support\Facades\Request::file('warning_sound_file'));

        $file = $request->file('warning_sound_file');
        // dd($file->getClientOriginalName());
        dd($file->getClientOriginalName());
        //Display File Name
//        echo 'File Name: '.$file->getClientOriginalName();
//        echo '<br>';
//
//        //Display File Extension
//        echo 'File Extension: '.$file->getClientOriginalExtension();
//        echo '<br>';
//
//        //Display File Real Path
//        echo 'File Real Path: '.$file->getRealPath();
//        echo '<br>';
//
//        //Display File Size
//        echo 'File Size: '.$file->getSize();
//        echo '<br>';
//
//        //Display File Mime Type
//        echo 'File Mime Type: '.$file->getMimeType();

        //Move Uploaded File
        //$destinationPath = 'uploads';
        //$file->move($destinationPath,$file->getClientOriginalName());

        /**
         * Upload Sound file to AWS S3 storage
         * S3 storage folder name is "sensor_sounds"
         */

//        $storage = Storage::disk('s3');
//
//        $file = Request::file('filefield');
//        $extension = $file->getClientOriginalExtension();
//        // Storage::disk('local')->put($file->getFilename().'.'.$extension,  File::get($file));
//        $storage->put($file->getFilename().'.'.$extension,  File::get($file));

//        // Upload sound file and contact as json to AWS s3 storage
//        $storage = Storage::disk('s3');
//        // Upload sound file as public access
//        $uploadedSound = $storage->put('sounds/' . $soundFilename, fopen($soundFileObject->getRealPath(), 'r+'), 'public');
//        // Upload contact as private access
//        $uploadedPhoneContact = $storage->put('phone_contacts/' . $phoneContactFileName, $phoneContactJson);
//        // Check if we upload successfully
//        if ($uploadedSound == true && $uploadedPhoneContact == true) {
//            // Create resource information in CallFlow table
//            $callFlowId = $this->callFlow->create(1, $soundFilename, $phoneContactFileName, $activityId, $retryDifferentTime);
//            // Get content of contacts.json file: phone number
//            // For get contents of AWS s3 private file content we must use AWS S3 Client
//            $s3Client = new S3Client(['credentials' => ['key' => env('S3_KEY'),
//                                                        'secret' => env('S3_SECRET')],
//                                                        'region' => env('S3_REGION'),
//                                                        'version' => '2006-03-01',
//                                                        // latest AWS S3 API Version :http://docs.aws.amazon.com/aws-sdk-php/v3/api/api-s3-2006-03-01.html
//                                    ]);
//            // Use FlySystem to upload and download file from AWS s3
//            $adapter = new AwsS3Adapter($s3Client, env('S3_BUCKET'));
//            // AWS s3 Adapter for FlySystem
//            $filesystem = new Filesystem($adapter);
//            $contacts = json_decode($filesystem->read('phone_contacts/' . $phoneContactFileName));
//            foreach ($contacts as $contact)
//            {
//                $this->phoneCall->create($numberOfRetry, $contact->phone, 'queued', 0, Carbon::now()->toDateTimeString(), $retryDifferentTime, $callFlowId);
//            }
//        }




    }
}
