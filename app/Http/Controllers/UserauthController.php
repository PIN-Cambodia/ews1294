<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use Session;
/* Calling user model to be used */
use App\User;

use App\Role;
use App\Permission;

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
  * Register new user function
  * @param $resquest : store form value submitted from view
  */
  public function registerAuth(Request $request)
  {
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
    //  $role_pcdm->attachPermission($Permission_upload->id);
    }
    //return redirect()->intended('register');
    //return redirect()->intended('register')->withErrors("Successful register new user");
    // return Redirect::to('register')->with('message', 'Successful register new user');
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

}
