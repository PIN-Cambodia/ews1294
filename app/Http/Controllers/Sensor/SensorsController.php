<?php

namespace App\Http\Controllers\Sensor;

use App\Models\Sensors;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Session;

class SensorsController extends Controller
{
    /*
  * Display users available in system based on user role
  */
    public function displaySensorInfoById(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $sensor_by_id = Sensors::where('id','=', $request->id)->first();
        // Data to be displayed in body and footer of modal
        $user_profile_data =    "<div class='modal-body'>"
            . "<input type='text' id='txtLocationCode' name='locationCode' value='" . $sensor_by_id->location_code . "' /><br />"
            . "<input type='text' id='txtAdditionalLocationInfo' name='additionalLocationInfo' value='" . $sensor_by_id->additional_location_info . "' /><br />"
            . "<input type='text' id='txtLocationCoordinates' name='locationCoordinates' value='" . $sensor_by_id->location_coordinates . "' /><br />"
            . "</div>"
            . "<div class='modal-footer'>"
            . "<button class='btn btn-default' data-dismiss='modal'>
                                  <i class='fa fa-times fa-lg' aria-hidden='true'></i> "
            . trans('auth.cancel')
            ."</button>"
            . "<button class='btn btn-primary' data-dismiss='modal' id='save_change_sensor' name='". $sensor_by_id->id ."'>
                                  <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i> "
            . trans('auth.save')
            ."</button>"
            . "</div>";
        return $user_profile_data;
    }

    /*
 * Function to verify csrf token when Ajax post data to controller
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

    /*
  * Function to Save
  */
    public function saveChangeSensorInfo(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $sensor_info = Sensors::where('id','=', $request->id)->first();
        var_dump($sensor_info);
        $sensor_info->location_code = $request->sensor_code;
        $sensor_info->additional_location_info = $request->sensor_loc;
        $sensor_info->location_coordinates = $request->sensor_coordinates;
        $sensor_info->save();
        $saved_sensor_info =    "<div class='modal-body'>"
            . "<input type='text' id='txt_user_name' name='username' value='" . $sensor_info->location_code . "' /><br />"
            . "<input type='text' id='txt_user_email' name='useremail' value='" . $sensor_info->additional_location_info . "' /><br />"
            . "<input type='text' id='txt_user_email' name='useremail' value='" . $sensor_info->location_coordinates . "' /><br />"
            //. "<button class='btn buttonAsLink'> Change Password </button>"
            . "</div>"
            . "<div class='modal-footer'>"
            . "<button class='btn btn-default' data-dismiss='modal'>
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

    public function getSensors(){
        $sensors = Sensors::all();
        return $sensors;
    }

    public function deleteSensor(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $delete_sensor_info = Sensors::where('id','=', $request->delete_val)->first();
        $delete_sensor_info->delete();

        return $delete_sensor_info;
    }

}
