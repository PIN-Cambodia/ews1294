<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Socialite;
use App\Models\activities;
use Auth;
use Redirect;
use Illuminate\Support\Facades\Input;
use Response;

class SoundfileCtrl extends Controller
{

    /**
     * Function to retrieve a list of provinces from database
     * @param Request $request
     * @return array of provinces to view, named "uploadSoundFile"
     */
    public function getProvinces()
    {
        $provinces = DB::table('province')->select('PROVINCE_KH')->get();
        return view('uploadSoundFile', ['provinces' => $provinces]);
    }

    /**
     * Function to create activity before making calls to villagers
     * @param Request $request
     * @return array of activity id and filename of sound file
     */
    public function insertNewActivity(Request $request)
    {
        if (Session::token() != $request->input('_token')) {
            return response()->json(array('msg' => 'Unauthorized attempt to create setting'));
        }
        $communes = Input::get('communes');
        $noOfPhones = Input::get('noOfPhones');
        $newfilename = $request->file('soundFile')->getClientOriginalName();
        // Upload sound file to AWS s3 storage
        $storage = Storage::disk('s3');
        $storage->put('sounds/' . $newfilename, fopen($request->file('soundFile')->getRealPath(), 'r+'), 'public');
        // Create new record in activities table
        $activities = new activities;
        $activities->manual_auto = 1;
        $activities->user_id = Auth::user()->id;
        $activities->list_commune_codes = $communes;
        $activities->no_of_phones_called = $noOfPhones;
        $activities->sound_file = $newfilename;
        $activities->save();

        return array($activities->id, $newfilename);
    }

    // test sending email testing

     // public function sendEmailAutomatichly(){
     //  require 'Mailer/Sendemail.php';

   
     //    $org ="ews";
     //    $email = "chenda.loeurt@gmail.com";
     //    $title = "email automatich when select commune that effictive";
     //    $body = "Hello world";
     //    $fname = "chenda";
       

     //    $send = Sendemail($org, $email, $fname, $title, $body);
     //    if ($send) {
     //        return redirect('uploadSoundFile')->with('message','you have successful contact us');
     //    }else{
     //       return redirect('uploadSoundFile')->with('message','Your message has been sent successful. Thank you.');
     //    }
 
    
}

}
