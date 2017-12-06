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
         // $activities=DB::table('activities')->select('sound_file','list_commune_codes')->get();
         // foreach ($activities as  $value) {
         //    $filename=$value->sound_file;
         //    $commune=$value->list_commune_codes;
         //     # code...
         // }
        if (Session::token() != $request->input('_token')) {
            return response()->json(array('msg' => 'Unauthorized attempt to create setting'));
        }
        $communes = Input::get('communes');
        $noOfPhones = Input::get('noOfPhones');
        $newfilename = $request->file('soundFile')->getClientOriginalName();
        // use condition not allow send the same file name in the same commune
        // if ($communes == $commune && $newfilename == $filename) {
        //     # code...
        //     echo "you can't send the record in the same commune";
        // }
        // else{
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
        // }//end condition send the same file name
    }

}
