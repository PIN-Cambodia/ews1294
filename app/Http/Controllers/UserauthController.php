<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
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
  public function loginauth(Request $request)
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
  public function registerauth(Request $request)
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
    if($user_role_type==2)
    {
      $role_pcdm =  Role::where('name', '=', 'PCDM')->first();
      $new_user->attachRole($role_pcdm->id);
    //  $role_pcdm->attachPermission($Permission_upload->id);
    }
    //return redirect()->intended('register');
    //return redirect()->intended('register')->withErrors("Successful register new user");
    return Redirect::to('register')->with('message', 'Successful register new user');
  }

  /*
  * Logout function
  *
  */
  public function logoutauth()
  {
    Auth::logout();
    return redirect()->intended('home');
  }

  /*
  * Display users available in system based on user role
  *
  */
  public function userlists()
  {

    $all_users = User::where('name', '!=', 'admin')->get();
    // dd($all_users);
    return view('usermgmt/userlists',['userlists' => $all_users]);
    // return view('usermgmt/userlists')->with('all_users',$all_users);
    // return view('users/userlists', compact($test));
  }


}
