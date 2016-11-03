<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Session;
use App;
//use URL;
use Redirect;

/* Calling user model to be used */
use App\User;
use App\Role;
use App\Permission;
use DB;

use App\Http\Requests;

use Illuminate\Http\Request;

class UserauthController extends Controller
{
  /*
  * Login function
  * @param $resquest : store form value submitted from view
  */
  public function loginAuth(Request $request)
  {
    if(!empty($request->remember)) $remember = true;
    else $remember = false;
    if(Auth::attempt(['name'=> $request->username, 'password' => $request->password], $remember))
    {
      // redirect to upload sound file page
      return redirect()->intended('soundFile');
    }
    else {
      // redirct to the back to the login form
      return redirect()->intended('login');
    }
  }

  /*
  * Register new user to EWS system function
  * @param $resquest : store form value submitted from view
  */
  public function registerAuth(Request $request)
  {
     // dd($request);
    /* insert registration data into table users in databaase */
    $new_user = new User;
    $new_user -> name = $request->name;
    $new_user -> email = $request->email;
    $new_user -> password = Hash::make($request -> password);
    $new_user -> save();
    /* end of insertion */

    /* adding role admin to this register user and
        set permission to manage all user to this user
        Role:
        - NCDM can manage PCDM users and Upload sound file for whole province
        - PCDM can upload sound file for only his/her authorized province
    */
    //$Permission_upload = Permission::where('name','upload-sound-files')->first();
    $user_role_type = $request->user_role_type;
    // In case of NCDM user role
    if($user_role_type==1)
    {
      $role_ncdm =  Role::where('name', '=', 'NCDM')->first();
      $permission_ncdm = Permission::where('name','manage-pcdm-users')->first();
      $new_user->attachRole($role_ncdm->id);
      //$role_ncdm->attachPermissions(array($permission_ncdm->id, $Permission_upload->id));
    }
    // In case of PCDM user role
    if($user_role_type == 2)
    {
        $role_pcdm =  Role::where('name', '=', 'PCDM')->first();
        $new_user->attachRole($role_pcdm->id);

        // insert province code for which PCDM user is authorized into table user role.
        $add_province_into_role_user = DB::table('role_user')
                                    -> where('user_id',$new_user->id)
                                    -> where('role_id',$role_pcdm->id)
                                    -> update(['province_code' => $request->authorized_province]);
        //  $role_pcdm->attachPermission($Permission_upload->id);
    }
    return view('auth/register')->with('message', 'Successful register new user');
  }

  /*
  * Logout function
  */
  public function logoutAuth()
  {
    Auth::logout();
    return redirect()->intended('home');
  }

  /*
  * Reset password function
  */
  public function resetPassword()
  {

  }


  /*
  * Display users available in system based on user role
  */
  public function userLists()
  {
    if(Auth::user()->hasRole('admin'))
    {
      $all_users = User::all();
    }
    if(Auth::user()->hasRole('NCDM'))
    {
      $all_users = User::where('name', '!=', 'admin')
                  -> Where('is_delete', '!=', '1')
                  -> get();
    }
    return view('auth/userlists',['userlists' => $all_users]);
  }

  /*
  * Display users available in system based on user role
  */
  public function displayUserProfiles(Request $request)
  {
    $this->checkCsrfTokenFromAjax($request->input('_token'));

    $user_data = User::where('id','=', $request->uid)->first();
    // Data to be displayed in body and footer of modal
    $user_profile_data =    "<div class='modal-body'>"
                              . "<input type='text' id='txt_user_name' name='username' value='" . $user_data->name . "' /><br />"
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

  /*
  * Function to Save
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

  /*
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

  /*
  * Delete users
  * If a user is deleted by NCDM then all user data is still in the system
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
    if(Auth::user()->hasRole('NCDM'))
    {
      $delete_user_data -> is_delete = 1;
      $delete_user_data -> save();
    }
    return $this->userlists();
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
    * get list of province for PCDM role in Registration view
    */
    public function getAuthorizedProvince(Request $request)
    {
        //dd($request->input('_token'));
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
        //dd($pro_select_option);
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

    /*
    * Change Language Locale when flag icon is clicked
    */
    public function changeLanguage(Request $request)
    {
        // dd(URL::previous());
        //if($request->flag_icon=='km')
        //App::setLocale('$request->flag_icon');
        \Session::put('locale', $request->flag_icon);
        return Redirect::back();
    }


}
