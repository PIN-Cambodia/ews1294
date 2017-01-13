<?php
namespace App\Http\Controllers\Sensor;

use App\Models\Sensors;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Session;

class SensorsController extends Controller
{
    /**
     * Function to display sensor info
     * @param $request sensor id to select its data.
     * @return string HTML code.
     */
    public function displaySensorInfoById(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));
        $sensor_by_id = Sensors::where('id','=', $request->id)->first();
        $get_existing_location = SensorTriggerController::getExistingProvinceAndDistrict($sensor_by_id->location_code,$sensor_by_id->location_code,false);
        // Data to be displayed in body and footer of modal
        $sensor_data = "<div class='modal-body'>"
            . trans('sensors.location')
            . "<div class='row'>"
                . "<div class='col-lg-3'>"
                    . "<select class='fullwidth select_style ss_province' id='ss_province'>"
                        . $get_existing_location['prov_options']
                        . $get_existing_location['other_prov_options']
                    . "</select>"
                . "</div>"
                . "<div class='col-lg-3'>"
                    . "<select class='fullwidth select_style ss_district ss_district_select' id='ss_district_select'>"
                        . $get_existing_location['dis_options']
                        . $get_existing_location['other_dis_options']
                    . "</select>"
                . "</div>"
                . "<div class='col-lg-6 ss_commune_select_div' id='ss_commune_select_div'>"
                    . "<select class='fullwidth select_style ss_commune_select' id='ss_commune_option' name='ss_commune_option'>"
                        . $get_existing_location['commune_val']
                        . $get_existing_location['other_commune_val']
                    . "</select>"
                . "</div>"
            . "</div><br />" // /. affected_communes div
            . trans('sensors.additional_Info')
            .": <input type='text' id='txtAdditionalLocationInfoEdit' name='additionalLocationInfo' value='" . $sensor_by_id->additional_location_info . "' /><br />"
            . trans('sensors.location_coordinates')
            .": <input type='text' id='txtLocationCoordinatesEdit' name='locationCoordinates' value='" . $sensor_by_id->location_coordinates . "' /><br />"
            . "</div>"
            . "<div class='modal-footer'>"
            . "<button class='btn btn-default' data-dismiss='modal'  name='". $sensor_by_id->id ."'>
                 <i class='fa fa-times fa-lg' aria-hidden='true'></i> "
            . trans('auth.cancel')
            ."</button>"
            . "<button class='btn btn-primary' data-dismiss='modal' id='save_change_sensor' name='". $sensor_by_id->id ."'>
                  <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i> "
            . trans('auth.save')
            ."</button>"
            . "</div>"
            . "<script src='/js/custom.js'></script>"
            ;
        return $sensor_data;
    }

    /**
     * Function to verify csrf token when Ajax post form data to controller.
     * @param $token token to be verified.
     * @return json message as unauthorized if token is invalid.
     */
    public function checkCsrfTokenFromAjax($token)
    {
        if(Session::token() !== $token)
        {
            return response()->json(array(
                'msg' => 'Unauthorized attempt to create setting'
            ));
        }
    }

    /**
     * Function to Save Sensor Info.
     * @param  $request is sent from Form.
     * @return json message as unauthorized if token is invalid.
     */
    public function saveChangeSensorInfo(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));
        $sensor_info = Sensors::where('id','=', $request->id)->first();

        $sensor_info->location_code  = $request->ccode;
        $sensor_info->additional_location_info = $request->additon_loc_info;
        $sensor_info->location_coordinates = $request->sensor_coordinates;
        $sensor_info->save();
        $saved_sensor_info =    "<div class='modal-body'>"
            . "<input type='text' id='txt_location_code' name='location_code' value='" . $sensor_info->location_code . "' /><br />"
            . "<input type='text' id='txt_additional_location_info' name='additional_location_info' value='" . $sensor_info->additional_location_info . "' /><br />"
            . "<input type='text' id='txt_location_coordinates' name='location_coordinates' value='" . $sensor_info->location_coordinates . "' /><br />"
            . "</div>"
            . "<div class='modal-footer'>"
            . "<button class='btn btn-default' data-dismiss='modal' >
              <i class='fa fa-times fa-lg' aria-hidden='true'></i> "
            . trans('auth.cancel')
            ."</button>"
            . "<button class='btn btn-primary' data-dismiss='modal' id='save_user_data' name='". $sensor_info->id ."'>
              <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i> "
            . trans('auth.save')
            ."</button>"
            . "</div>";
        return $saved_sensor_info;
    }

    /**
     * Function to get all sensors from Database.
     * @return A collection of sensors
     */
    public function getSensors(){
        $sensors = Sensors::all();
        return $sensors;
    }

    /**
     * Function to delete sensor from Database.
     * @param $request sensor id to be deleted.
     * @return deleted sensor id
     */
    public function deleteSensor(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));
        $delete_sensor_info = Sensors::where('id','=', $request->delete_val)->first();
        $delete_sensor_info->delete();
        return $delete_sensor_info;
    }

    /**
     * Function to add new sensor into Database.
     * @param $request: sensor id and sensor info to be added.
     * @return $sensors->id: new sensor id that has been added
     */
    public function addNewSensor(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));
        $sensors = new Sensors;
        $sensors->sensor_id = $request->sensor_code;
        $sensors->location_code  = $request->commune_code;
        $sensors->additional_location_info = $request->sensor_additional_info;
        $coordidates = $request->sensor_lat .', '. $request->sensor_long;
        $sensors->location_coordinates = $coordidates;
        $sensors->save();

        return $sensors->id;
    }

    /**
     * Function to select all communes of one specific district.
     * @param $request: district id for retrieving its communes.
     * @return string of html code to display a drop down list.
     */
    public function getSSCommunesPerDistrict(Request $request)
    {
        $communes=DB::table('commune')->where('DCode', $request->distric_id)->get();
        $commune_options = "";
        if(!empty($communes))
        {
            foreach($communes as $commune)
            {
                if (App::getLocale()=='km')
                    $commune_options .= "<option value='". $commune->CCode . "'>" . $commune->CName_kh . "</option>";
                else
                    $commune_options .= "<option value='". $commune->CCode . "'>" . $commune->CName_en . "</option>";
            }
        }
        $commune_selection = "<option value='0'>" . trans('pages.select_communes') . "</option>" . $commune_options;
        return $commune_selection;
    }
}
