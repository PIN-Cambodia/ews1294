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
        // select sensors data
        $sensors_list = sensors::all();
        // select sensor triggers data
        $sensor_trigger = sensortriggers::orderBy('created_at', 'desc')->get();
        return view('sensor/sensortrigger', ['all_province' => $all_province, 'sensor_list' => $sensors_list, 'sensor_trigger_data' => $sensor_trigger]);
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
            $warning_file_name = 'wsf_' . date('m_d_Y_hia') . '.' . $warning_file->getClientOriginalExtension();
            $emergency_file_name = 'esf_' . date('m_d_Y_hia') . '.' . $emergency_file->getClientOriginalExtension();

            $storage = Storage::disk('s3');
            $upload_wsf = $storage->put('sensor_sounds/' . $warning_file_name, file_get_contents($warning_file));
            $upload_esf = $storage->put('sensor_sounds/' . $emergency_file_name, file_get_contents($emergency_file));
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
    public function editSensorTrigger(Request $request)
    {
        $option_list=""; $commune_checked="";
        $edit_sensor_id = $request->edit_val;
        // select sensor trigger record
        $ss_trigger_record = sensortriggers::where('id', $edit_sensor_id)->get();

        // get all sensor id except the edit sensor id
        $ss_record = sensors::where('sensor_id', '!=', $ss_trigger_record[0]->sensor_id)->get();
        foreach($ss_record as $ss_record)
        {
            $option_list .= "<option value='" . $ss_record->sensor_id . "'>" . $ss_record->sensor_id ."</option>";
        }
        // get existing record of province, district and checked the communes
        $affected_communes_arr = explode(",", $ss_trigger_record[0]->affected_communes);

        foreach($affected_communes_arr as $communes_list)
        {
//            $affected_commune = \DB::table('commune')->where('CCode', $affected_commune_list)->get();
//            if(!empty($affected_commune))
//            {
//                if (\App::getLocale()=='km')
//                    $affected_communes .= $affected_commune[0]->CName_kh . ", ";
//                else
//                    $affected_communes .= $affected_commune[0]->CName_en . ", ";
//            }
        }

       // dd($list_commune_str);
        DB::enableQueryLog();
        $affected_commune_list = DB::table('commune')->whereIn('CCode', array($list_commune_str))->get();
        dd(DB::getQueryLog());

        //dd($affected_commune_list);
        $modal_body = "<div class='modal-body'>"
                        . trans('sensors.sensor_id')
                        . "<select class='fullwidth select_style' id='sensor_id' name='sensor_id'>"
                            . "<option value='" . $ss_trigger_record[0]->sensor_id . "' selected>"
                                . $ss_trigger_record[0]->sensor_id
                            . "</option>"
                            . $option_list
                        . "</select><br />"
                        . trans('sensors.warning_level')
                        . "<input type='text' id='warning_level' name='warning_level' value='" . $ss_trigger_record[0]->level_warning . "'/><br />"
                        . trans('sensors.emergency_level')
                        . "<input type='text' id='emergency_level' name='emergency_level' value='" . $ss_trigger_record[0]->level_emergency . "'/><br />"
                        . trans('sensors.affected_communes')

                    . "</div>" // /.modal-body
                    ;
        return $modal_body;
    }

    /**
     * Save Edit Sensor triggers record
     * Allow admin user to change sound file
     * @param Request $request
     */
    public function saveEditSensorTrigger(Request $request)
    {

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
