<?php

namespace App\Http\Controllers\Sensor;

use App\Models\province;
use App\Models\Sensors;
use App\Models\sensortriggers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Validator;

class SensorTriggerController extends Controller
{
    /**
     * SensorTriggerController constructor.
     */
    public function __construct()
    {
        // Write Log into a user defined log file i.e storage/log/sensor_trigger_log.log
        $this->logger = new Logger('sensor_trigger_log');
        $this->logger->pushHandler(new StreamHandler(storage_path('logs/sensor_trigger_log.log')),Logger::INFO);
    }

    /**
     * Display all sensor trigger data in dataTable
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sensorTriggerReport()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM')) $all_province = province::all();

        if(Auth::user()->hasRole('PCDM'))
        {
            $pcdm_province=DB::table('role_user')->where('user_id', Auth::user()->id)->first();
            $all_province=DB::table('province')->where('PROCODE', $pcdm_province->province_code)->get();
        }

        // No duplicate sensor id record allows to be added in sensortriggers
        $sensor_list_add_new = DB::table('sensors')
                                -> select('sensors.sensor_id')
                                -> leftJoin('sensortriggers', 'sensors.sensor_id', '=', 'sensortriggers.sensor_id')
                                -> whereNull('sensortriggers.sensor_id')
                                -> get();
        // select sensor triggers data
        $sensor_trigger = sensortriggers::orderBy('id', 'desc')->get();
        return view('sensor/sensortrigger', ['all_province' => $all_province, 'sensor_list_add_new' => $sensor_list_add_new, 'sensor_trigger_data' => $sensor_trigger]);
    }

    /**
     * Select Districts of a province
     * @param Request $request
     * @return string
     */
    public function getDistrictPerProvince(Request $request)
    {
        $districts=DB::table('district')->where('PCode', $request->province_id)->get();
        $district_options = "";
        if(!empty($districts))
        {
            foreach($districts as $district)
            {
                if (App::getLocale()=='km')
                    $district_options .= "<option value='". $district->DCode . "'>" . $district->DName_kh . "</option>";
                else
                    $district_options .= "<option value='". $district->DCode . "'>" . $district->DName_en . "</option>";
            }
        }
        $district_selection = "<option value='0'>" . trans('pages.select_district') . "</option>" . $district_options;
        return $district_selection;
    }

    /**
     * Select Communes of a district
     * @param Request $request
     * @return string
     */
    public function getCommunesPerDistrict(Request $request)
    {
        // DB::enableQueryLog();
        $communes=DB::table('commune')->where('DCode', $request->distric_id)->get();
        // dd(DB::getQueryLog());
        $commune_options = "";
        if(!empty($communes))
        {
            foreach($communes as $commune)
            {
                if (App::getLocale()=='km')
                    $commune_options .= "<input type='checkbox' name='communes[]' value='". $commune->CCode . "'> " . $commune->CName_kh . "<br/>";
                else
                    $commune_options .= "<input type='checkbox' name='communes[]' value='". $commune->CCode . "'> " . $commune->CName_en . "<br/>";
            }
        }
        return $commune_options;
    }

    /**
     * Add new sensor trigger into table sensortriggers
     * Upload sound file into AWS S3
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addSensorTrigger(Request $request)
    {
        /** upload file to AWS S3 **/
        if ($request->hasFile('warning_sound_file') && $request->hasFile('emergency_sound_file')) {
            $warning_file = $request->file('warning_sound_file');
            $emergency_file = $request->file('emergency_sound_file');
            $warning_file_name = substr($warning_file->getClientOriginalName(),0,-4) . '_' . date('m-d-Y_h:ia') . '.' . $warning_file->getClientOriginalExtension();
            $emergency_file_name = substr($emergency_file->getClientOriginalName(),0,-4) . '_' . date('m-d-Y_h:ia') . '.' . $emergency_file->getClientOriginalExtension();

            $storage = Storage::disk('s3');
            //$upload_wsf = $storage->put('sensor_sounds/' . $warning_file_name, file_get_contents($warning_file));
            //$upload_esf = $storage->put('sensor_sounds/' . $emergency_file_name, file_get_contents($emergency_file));
            try{
                $upload_wsf = $storage->put('sensor_sounds/' . $warning_file_name, file_get_contents($warning_file));
                $upload_esf = $storage->put('sensor_sounds/' . $emergency_file_name, file_get_contents($emergency_file));
            }
            catch (\Exception $e) {
                $this->logger->addError("Upload Sound file Error: " . $e->getMessage() . " in " . $e->getFile());
            }


        }

        /** insert data into table **/
        // Affected Communes list
        $communes_list="";
        $commune_arr = $request->communes;
        if(!empty($commune_arr))
        {
            foreach($commune_arr as $commune)
            {
                // if last element of array then no need to append ","
                if ($commune === end($commune_arr))
                    $communes_list .= $commune;
                else $communes_list .= $commune . ",";
            }
        }
        // insert data
        try {
            $sensor_trigger_tbl = new sensortriggers;
            $sensor_trigger_tbl->sensor_id = $request -> sensor_id;
            $sensor_trigger_tbl->level_warning = $request -> warning_level;
            $sensor_trigger_tbl->level_emergency = $request -> emergency_level;
            $sensor_trigger_tbl->affected_communes = $communes_list;
            $sensor_trigger_tbl->phone_numbers = $request -> phone_numbers;

            if(!empty($upload_wsf)== true) $sensor_trigger_tbl->warning_sound_file = $warning_file_name;
            if(!empty($upload_esf)== true) $sensor_trigger_tbl->emergency_sound_file = $emergency_file_name;

            $sensor_trigger_tbl->emails_list = $request -> email_list;
            $sensor_trigger_tbl->save();

        }
        catch (\Exception $e) {
            $this->logger->addError("Inserted Data Error: " . $e->getMessage() . " in " . $e->getFile());
        }
        return redirect()->intended('/sensortrigger');
    }

    /**
     * Get Edit Sensor triggers record
     * Allow admin user to change sound file
     */
    public function getEditSensorTrigger(Request $request)
    {
        $edit_sensor_id = $request->edit_val;

        $commune_checkbox=""; $prov_options=""; $dis_options="";$province_substr_len=0;$district_substr_len=0;
        $other_prov_options =""; $other_dis_options=""; $other_commune_checkbox="";

        // select sensor trigger record
        $ss_trigger_record = sensortriggers::where('id', $edit_sensor_id)->get();

        // get existing record of province, district and checked the communes
        $affected_communes_arr = explode(",", $ss_trigger_record[0]->affected_communes);
        /** Since affected list of communes is within a single district and province,
        * so getting the province and district code from only 1st element of affected communes array
         **/


            //dd(strlen($affected_communes_arr[0]));
            // for province code from 1-> 9
            if(strlen($affected_communes_arr[0]) == 5)
            {
                $province_substr_len = 1;
                $district_substr_len = 3;
            }
            // for province code from 10 upward
            elseif(strlen($affected_communes_arr[0]) == 6)
            {
                $province_substr_len = 2;
                $district_substr_len = 4;
            }
            $province_str_code = substr($affected_communes_arr[0],0,$province_substr_len);
            $district_str_code = substr($affected_communes_arr[0],0,$district_substr_len);
            /** select Existed Province and District data record **/
            if(!empty($province_str_code))
            {
                $province_query = DB::table('province')->where('PROCODE', $province_str_code)->get();
                if(!empty($province_query))
                {
                    if (App::getLocale()=='km')
                        $prov_options = "<option value='". $province_query[0]->PROCODE . "' selected>" . $province_query[0]->PROVINCE_KH . "</option>";
                    else
                        $prov_options = "<option value='". $province_query[0]->PROCODE . "' selected>" . $province_query[0]->PROVINCE . "</option>";
                }
                //else "<option value='0'>" . trans('pages.select_province') . "</option>";
            }
            else $prov_options = "<option value='0'>" . trans('pages.select_province') . "</option>";

            if(!empty($district_str_code)) {
                $district_query = DB::table('district')->where('DCode', $district_str_code)->get();
                if (!empty($district_query)) {
                    if (App::getLocale() == 'km')
                        $dis_options = "<option value='" . $district_query[0]->DCode . "' selected>" . $district_query[0]->DName_kh . "</option>";
                    else
                        $dis_options = "<option value='" . $district_query[0]->DCode . "' selected>" . $district_query[0]->DName_en . "</option>";
                }
            }
            /** select other Provinces and Districts data **/
            //DB::enableQueryLog();
            $other_province_queries = DB::table('province')->where('PROCODE','!=', $province_str_code)->get();
            //dd(DB::getQueryLog());

            if(!empty($other_province_queries))
            {
                foreach($other_province_queries as $other_province_query)
                {
                    if (App::getLocale()=='km')
                        $other_prov_options .= "<option value='". $other_province_query->PROCODE . "'>" . $other_province_query->PROVINCE_KH . "</option>";
                    else
                        $other_prov_options .= "<option value='". $other_province_query->PROCODE . "'>" . $other_province_query->PROVINCE . "</option>";
                }
            }
            //DB::enableQueryLog();
            $other_district_queries = DB::table('district')->where('PCode', $province_str_code)
                                        ->where('DCode', '!=', $district_str_code)
                                        ->get();
            //dd(DB::getQueryLog());
            if(!empty($other_district_queries))
            {
                foreach($other_district_queries as $other_dis_option)
                {
                    if (App::getLocale()=='km')
                        $other_dis_options .= "<option value='". $other_dis_option->DCode . "'>" . $other_dis_option->DName_kh . "</option>";
                    else
                        $other_dis_options .= "<option value='". $other_dis_option->DCode . "'>" . $other_dis_option->DName_en . "</option>";
                }
            }

            // commune
            $affected_commune_list = DB::table('commune')->whereIn('CCode', $affected_communes_arr)->get();
            if(!empty($affected_commune_list))
            {
                foreach($affected_commune_list as $affected_commune )
                {
                    if (\App::getLocale()=='km')
                        $commune_checkbox .= "<input type='checkbox' name='communes[]' value='". $affected_commune->CCode . "' checked> " . $affected_commune->CName_kh . "<br/>";
                    else
                        $commune_checkbox .= "<input type='checkbox' name='communes[]' value='". $affected_commune->CCode . "' checked> " . $affected_commune->CName_en . "<br/>";
                }
            }

            $other_communes = DB::table('commune') -> where('DCode', $district_str_code)
                                ->whereNotIn('CCode', $affected_communes_arr)
                                ->get();
            if(!empty($other_communes))
            {
                foreach($other_communes as $other_communes )
                {
                    if (\App::getLocale()=='km')
                        $other_commune_checkbox .= "<input type='checkbox' name='communes[]' value='". $other_communes->CCode . "'> " . $other_communes->CName_kh . "<br/>";
                    else
                        $other_commune_checkbox .= "<input type='checkbox' name='communes[]' value='". $other_communes->CCode . "'> " . $other_communes->CName_en . "<br/>";
                }
            }

        $modal_body = "<div class='modal-body'>"
                        . trans('sensors.sensor_id')
                        . "<input type='text' value='" . $ss_trigger_record[0]->sensor_id . "' disabled/><br />"
                        . "<input type='hidden' id='sensor_id' name='sensor_id' value='" . $ss_trigger_record[0]->sensor_id . "' />"
                        . trans('sensors.warning_level')
                        . "<input type='text' class='numeric' id='warning_level' name='warning_level' value='" . $ss_trigger_record[0]->level_warning . "'/><br />"
                        . trans('sensors.emergency_level')
                        . "<input type='text' class='numeric' id='emergency_level' name='emergency_level' value='" . $ss_trigger_record[0]->level_emergency . "'/><br />"
                        . trans('sensors.affected_communes') . "<br />"
                        . "<div class='row'>"
                            . "<div class='col-lg-3'>"
                                . "<select class='fullwidth select_style ss_province' id='ss_province'>"
                                    . $prov_options
                                    . $other_prov_options
                                . "</select>"
                            . "</div>"
                            . "<div class='col-lg-3'>"
                                . "<select class='fullwidth select_style ss_district' id='ss_district'>"
                                    . $dis_options
                                    . $other_dis_options
                                . "</select>"
                            . "</div>"
                            . "<div class='col-lg-6 ss_commune_div' id='ss_commune_div'>"
                                . "<div class='row select_style_height'>"
                                    . "<div class='col-lg-12 ss_commune' id='ss_commune' name='ss_communes'>"
                                    . $commune_checkbox
                                    . $other_commune_checkbox
                                    . "</div>"
                                . "</div>"
                            . "</div>"
                        . "</div><br />" // /. affected_communes div
                        . trans('sensors.phone_numbers')
                        // . "<input type='text' id='phone_numbers' name='phone_numbers' value='" . $ss_trigger_record[0]->phone_numbers . "'/><br />"
                        . "<textarea class='multinumbers' rows='4' cols='50' id='phone_numbers' name='phone_numbers' placeholder='"
                            . trans('sensors.enter_multiple_phone_numbers') . "'>" . $ss_trigger_record[0]->phone_numbers . " </textarea><br />"
                        . trans('sensors.sound_file_warning')
                        . "<div class='row' id='existing_warning_file'>"
                            . "<div class='col-lg-8' >"
                                . "<input type='text' value='" . $ss_trigger_record[0]->warning_sound_file . "' disabled/><br />"
                            . "</div>" // /.col-lg-8
                            . "<div class='col-lg-4'>"
                                . "<button class='btn btn-danger' id='change_warning_file'>"
                                    . "<i class='fa fa-upload fa-lg' aria-hidden='true'></i> "
                                    . trans('sensors.change_sound_file')
                                . "</button>"
                            . "</div>" // /.col-lg-4
                        . "</div>" // /.row
                        . "<div class='row' id='upload_warning_file'>"
                            . "<div class='col-lg-12' >"
                                . "<input type='file' id='warning_sound_file' name='warning_sound_file' accept='audio/*'/><br />"
                            . "</div>" // /.col-lg-12
                        . "</div>" // /.row
                        . trans('sensors.sound_file_emergency')
                        . "<div class='row' id='existing_emergency_file'>"
                            . "<div class='col-lg-8' >"
                                . "<input type='text' value='" . $ss_trigger_record[0]->emergency_sound_file . "' disabled/><br />"
                            . "</div>" // /.col-lg-8
                            . "<div class='col-lg-4'>"
                                . "<button class='btn btn-danger' id='change_emergency_file'>"
                                    . "<i class='fa fa-upload fa-lg' aria-hidden='true'></i> "
                                    . trans('sensors.change_sound_file')
                                . "</button>"
                            . "</div>" // /.col-lg-4
                        . "</div>" // /.row
                        . "<div class='row' id='upload_emergency_file'>"
                            . "<div class='col-lg-12' >"
                                . "<input type='file' id='emergency_sound_file' name='emergency_sound_file' accept='audio/*'/><br />"
                            . "</div>" // /.col-lg-12
                        . "</div>" // /.row
                        //. "<input type='text' value='" . $ss_trigger_record[0]->emergency_sound_file  . "' disabled/><br />"
                        . trans('sensors.emails')
                        . "<textarea rows='4' cols='50' id='email_list' name='email_list'>" . $ss_trigger_record[0]->emails_list . "</textarea><br />"
                    . "</div>" // /.modal-body
                    . "<script src='/js/custom.js'></script>"
                    ;
        return $modal_body;
    }

    /**
     * Save Edit Sensor triggers record
     * Allow admin user to change sound file
     * @param Request $request
     * @return reload page
     */
    public function saveEditSensorTrigger(Request $request)
    {

        // select sensor trigger record
       // DB::enableQueryLog();
        $ss_trigger_data = sensortriggers::where('sensor_id', $request->sensor_id)->first();
        //dd(DB::getQueryLog());
        //dd($ss_trigger_data);

        $storage = Storage::disk('s3');
        // user can either update warning or emergency or both
        if ($request->hasFile('warning_sound_file')) {
            $warning_file_new = $request->file('warning_sound_file');
            $warning_file_nam = substr($warning_file_new->getClientOriginalName(),0,-4) . '_' . date('m-d-Y_h:ia') . '.' . $warning_file_new->getClientOriginalExtension();

            try{
                // upload new warning sound file
                $upload_wsf_new = $storage->put('sensor_sounds/' . $warning_file_nam, file_get_contents($warning_file_new));

                // delete old existing sound file
                if($storage->has('sensor_sounds/'. $ss_trigger_data->warning_sound_file) == true)
                    $storage->delete('sensor_sounds/' . $ss_trigger_data->warning_sound_file);
            }
            catch (\Exception $e) {
                $this->logger->addError("ERROR: Upload New Sound File: " . $warning_file_nam
                    . " & Delete old file: " . $ss_trigger_data->warning_sound_file . " "
                    . $e->getMessage() . " in " . $e->getFile());
            }
        }
        if($request->hasFile('emergency_sound_file'))
        {
            $emergency_file_new = $request->file('emergency_sound_file');
            $emergency_file_nam = substr($emergency_file_new->getClientOriginalName(),0,-4) . '_' . date('m-d-Y_h:ia') . '.' . $emergency_file_new->getClientOriginalExtension();
            try{
                // upload new emergency sound file
                $upload_esf_new = $storage->put('sensor_sounds/' . $emergency_file_nam, file_get_contents($emergency_file_new));
                // delete old existing sound file
                if($storage->has('sensor_sounds/'. $ss_trigger_data->emergency_sound_file) == true)
                    $storage->delete('sensor_sounds/' . $ss_trigger_data->emergency_sound_file);
            }
            catch (\Exception $e) {
                $this->logger->addError("ERROR: Upload New Sound File: " . $emergency_file_nam
                                        . " & Delete old file: " . $ss_trigger_data->emergency_sound_file . " "
                                        . $e->getMessage() . " in " . $e->getFile());
            }
        }

        /** insert data into table **/
        // Affected Communes list
        $communes_list_new="";
        $commune_arr_new = $request->communes;
        if(!empty($commune_arr_new))
        {
            foreach($commune_arr_new as $commune)
            {
                // if last element of array then no need to append ","
                if ($commune === end($commune_arr_new))
                    $communes_list_new .= $commune;
                else $communes_list_new .= $commune . ",";
            }
        }
        //dd($communes_list_new);
        // insert data
        try {
            $ss_trigger_data->level_warning = $request -> warning_level;
            $ss_trigger_data->level_emergency = $request -> emergency_level;
            $ss_trigger_data->affected_communes = $communes_list_new;
            $ss_trigger_data->phone_numbers = $request -> phone_numbers;

            if(!empty($upload_wsf_new)== true) $ss_trigger_data->warning_sound_file = $warning_file_nam;
            if(!empty($upload_esf_new)== true) $ss_trigger_data->emergency_sound_file = $emergency_file_nam;

            $ss_trigger_data->emails_list = $request -> email_list;
            $ss_trigger_data->save();

        }
        catch (\Exception $e) {
            $this->logger->addError("UPDATE DATA INTO TABLE ERROR: " . $e->getMessage() . " in " . $e->getFile());
        }
        // return $ss_trigger_data;
        return redirect()->intended('/sensortrigger');
    }

    /**
     * Delete selected records from Table sensortriggers
     * Delete related sound files from AWS S3
     * @param Request $request
     * @return mixed
     */
    public function deleteSensorTrigger(Request $request)
    {
        // select data that need to be deleted
        $delete_sensor_trigger = sensortriggers::where('id',$request->delete_val)->first();
        if(!empty($delete_sensor_trigger))
        {
            // Delete Warning and Emergency sound file from AWS S3
            $storage = Storage::disk('s3');
            $warning_file = $delete_sensor_trigger->warning_sound_file;
            $emergency_file = $delete_sensor_trigger->emergency_sound_file;
            try
            {
                // check if the sound file exists then delete
                if($storage->has('sensor_sounds/'. $warning_file) == true)
                    $storage->delete('sensor_sounds/' . $warning_file);
                if($storage->has('sensor_sounds/'. $emergency_file) == true)
                    $storage->delete('sensor_sounds/' . $emergency_file);
                // delete sensor trigger record
                $delete_sensor_trigger->delete();
            }
            catch (\Exception $e) {
                $this->logger->addError("Delete sensor trigger Erorr: " . $e->getMessage() . " in " . $e->getFile());
            }
        }
    }
}
