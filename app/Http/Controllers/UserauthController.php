<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Session;
use App;
use Redirect;
use App\User;
use App\Role;
use App\Permission;
use DB;
use Illuminate\Http\Request;

class UserauthController extends Controller
{
    /**
    * Login function
    * @param Request $request: store form value submitted from view
    * @return data into view
    */
    public function loginAuth(Request $request)
    {
        if(!empty($request->remember)) $remember = true;
        else $remember = false;
        $check_user = User::where('name', $request->username)->first();
        // check user who is disable or deleted by ncdm
        if(!empty($check_user))
        {
            if($check_user->is_disable == 1) return view('auth/login',['disable_user_error' => trans('pages.disable_user')]);
            elseif($check_user->is_delete == 1) return view('auth/login',['delete_user_error' => trans('pages.delete_user')]);
        }

        if(Auth::attempt(['name'=> $request->username, 'password' => $request->password], $remember))
            return redirect()->intended('soundFile');
        else
            return view('auth/login',['invalid_username_password_error' => trans('auth.incorrect_username_password')]);
    }

    /**
     * Register new user to EWS system function
     * @param Request $request
     * @return data into view
     */
    public function registerAuth(Request $request)
    {
        /* insert registration data into table users in database */
        $new_user = new User;
        $new_user -> name = $request->name;
        $new_user -> email = $request->email;
        $new_user -> password = Hash::make($request -> password);
        $new_user -> save();

        // adding either NCDM or PCDM user role to the register user
        $user_role_type = $request->user_role_type;
        // In case of NCDM user role
        if($user_role_type==1)
        {
            $role_ncdm =  Role::where('name', '=', 'NCDM')->first();
            $new_user->attachRole($role_ncdm->id);
            // $permission_ncdm = Permission::where('name','manage-pcdm-users')->first();
            // $role_ncdm->attachPermissions(array($permission_ncdm->id, $Permission_upload->id));
        }
        // In case of PCDM user role
        if($user_role_type == 2)
        {
            $role_pcdm =  Role::where('name', '=', 'PCDM')->first();
            $new_user->attachRole($role_pcdm->id);
            // insert province code for which PCDM user is authorized into table user role.
            DB::table('role_user')
                -> where('user_id',$new_user->id)
                -> where('role_id',$role_pcdm->id)
                -> update(['province_code' => $request->authorized_province]);
            // $Permission_upload = Permission::where('name','upload-sound-files')->first();
            //  $role_pcdm->attachPermission($Permission_upload->id);
        }
        return view('auth/register',['successfully_register_new_user' => trans('auth.successfully_register_new_user')]);
    }

    /**
    * Logout function
    */
    public function logoutAuth()
    {
        Auth::logout();
        return redirect()->intended('home');
    }

    /**
    * Display users available in system based on user role
     * Notes that only Admin has rights to manage users
     * @return list of users
    */
    public function userLists()
    {
        if(Auth::user()->hasRole('admin'))
            $all_users = User::whereNotIn('name', ['Chris Sevilleja','Twilio user','Sensor user'])-> get();
        else $all_users = "";
        return view('auth/userlists',['userlists' => $all_users]);
    }

    /**
    * Display users available in system based on user role
     * @param Request $request
     * @return user profile data
    */
    public function displayUserProfiles(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $user_data = User::where('id','=', $request->uid)->first();
        // Data to be displayed in body and footer of modal
        $user_profile_data =    "<div class='modal-body'>"
                                    . trans('auth.username')
                                    . "<input type='text' id='txt_user_name' name='username' value='" . $user_data->name . "' /><br />"
                                    . trans('auth.email')
                                    . "<input type='text' id='txt_user_email' name='useremail' value='" . $user_data->email . "' /><br />"
                                  //. "<button class='btn buttonAsLink'> Change Password </button>"
                                . "</div>"
                                . "<div class='modal-footer'>"
                                  . "<button class='btn btn-default' data-dismiss='modal'>
                                      <i class='fa fa-times fa-lg' aria-hidden='true'></i> "
                                      . trans('auth.cancel')
                                  ."</button>"
                                  . "<button class='btn btn-primary' data-dismiss='modal' id='save_user_data' name='". $user_data->id ."'>
                                      <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i> "
                                      . trans('auth.save')
                                  ."</button>"
                                . "</div>";
        return $user_profile_data;
    }

    /**
    * Function to Save user profile
     * @param Request $request
     * @return saved data
    */
    public function saveUserProfile(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $save_user_data = User::where('id','=',$request->uid)->first();
        $save_user_data->name = $request->uname;
        $save_user_data->email = $request->uemail;
        $save_user_data->save();
        $new_user_profile_data =    "<div class='modal-body'>"
                                  . "<input type='text' id='txt_user_name' name='username' value='" . $save_user_data->name . "' /><br />"
                                  . "<input type='text' id='txt_user_email' name='useremail' value='" . $save_user_data->email . "' /><br />"
                                  //. "<button class='btn buttonAsLink'> Change Password </button>"
                                . "</div>"
                                . "<div class='modal-footer'>"
                                  . "<button class='btn btn-default' data-dismiss='modal'>
                                      <i class='fa fa-times fa-lg' aria-hidden='true'></i> "
                                      . trans('auth.cancel')
                                  ."</button>"
                                  . "<button class='btn btn-primary' data-dismiss='modal' id='save_user_data' name='". $save_user_data->id ."'>
                                      <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i> "
                                      . trans('auth.save')
                                  ."</button>"
                                . "</div>";
        return $new_user_profile_data;

    }

    /**
    * Enable or Disable a user
    */
    public function enableDisable(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $enable_disable_user = User::where('id','=',$request->btn_value)->first();
        $request_btn_name = $request->btn_name;

        if($request_btn_name=="disable_user")
        {
          $enable_disable_user -> is_disable = 1;
          $enable_disable_user -> save();
        }
        if($request_btn_name=="enable_user")
        {
          $enable_disable_user -> is_disable = 0;
          $enable_disable_user -> save();
        }
        return $this->userlists();
    }

    /**
    * Delete users
    * If Admin delete a user then that user will be permanently delted.
    */
    public function deleteUser(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));

        $delete_user_data = User::where('id','=', $request->delete_val)->first();
        if(Auth::user()->hasRole('admin'))
        {
          $delete_user_data->delete();
        }
        return $this->userlists();
    }

    /**
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

    /**
    * get list of province for PCDM role in Registration view
    */
    public function getAuthorizedProvince(Request $request)
    {
        $this->checkCsrfTokenFromAjax($request->input('_token'));
        $pro_select_option = "";
        $province_val= DB::table('province')->select('PROCODE', 'PROVINCE', 'PROVINCE_KH')->get();
        foreach ($province_val as $province_val)
        {
            if (App::getLocale()=='km')
                $pro_select_option .= "<option value=" . $province_val->PROCODE . ">" . $province_val->PROVINCE_KH . "</option>";
            else
                $pro_select_option .= "<option value=" . $province_val->PROCODE . ">" . $province_val->PROVINCE . "</option>";
        }
        $province_div = "<label for=\"authorized_province\" class=\"col-md-4 control-label\">"
                            . trans('auth.authorized_province')
                        . "</label>"
                        . "<div class=\"col-md-6\" id=\"select_pcdm_authorized_province\">"
                            . "<select name=\"authorized_province\" class=\"form-control\">"
                                . "<option value=\"0\">" . trans('auth.select_province') . "</option>"
                                . $pro_select_option
                            . "</select>"
                        . "</div>";

        return $province_div;
    }

    /**
    * Change Language Locale when flag icon is clicked
    */
    public function changeLanguage(Request $request)
    {
        \Session::put('locale', $request->flag_icon);
        return Redirect::back();
    }
}
