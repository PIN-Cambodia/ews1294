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
    // dump the given variable and end execution of the script
    //dd($request->_token);
    if(!empty($request->remember)) $remember = true;
    else $remember = false;
    if(Auth::attempt(['name'=> $request->username, 'password' => $request->password], $remember))
    {
      // Check user Role

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
    // dump the given variable and end execution of the script
    //dd($request->_token);
    //$user_info = User::all();
    //dd($user_info);

    /* --- Adding roles --- */
    // $admin = new Role();
    // $admin->name = 'admin';
    // $admin->display_name = "User Administrator";
    // $admin->description = "User is allowed to manage all functions in system";
    // $admin->save();

    // $ncdm = new Role();
    // $ncdm->name = 'NCDM';
    // $ncdm->display_name = "National Committee for Disaster Management";
    // $ncdm->description = "User is allowed to manage users, upload sound file and view report for nationwide provinces";
    // $ncdm->save();

    // $pcdm = new Role();
    // $pcdm->name = 'PCDM';
    // $pcdm->display_name = "Provincial Committee for Disaster Management";
    // $pcdm->description = "User is allowed to upload sound file and view report within his/her authorized province";
    // $pcdm->save();
    /* --- End of Adding roles ---  */

    /* --- Adding Permission */
    // $manageAllUser = new Permission();
    // $manageAllUser->name = 'manage-all-users';
    // $manageAllUser->display_name = 'Manage All Users'; // optional
    // $manageAllUser->description  = 'This right is only for Admin User'; // optional
    // $manageAllUser->save();
    //
    // $managePCDMUser = new Permission();
    // $managePCDMUser->name = 'manage-pcdm-users';
    // $managePCDMUser->display_name = 'Manage PCDM Users'; // optional
    // $managePCDMUser->description  = 'NCDM can manage all PCDM users'; // optional
    // $managePCDMUser->save();

    /* --- End of Adding Permission --- */

    /* insert registration data into table users in databaase */
    $new_user = new User;
    $new_user -> name = $request->name;
    $new_user -> email = $request->email;
    $new_user -> password = Hash::make($request -> password);
    // $new_user -> created_at = date("Y-m-d H:i:s");
    // $new_user -> updated_at = date("Y-m-d H:i:s");
    $new_user -> save();
    /* end of insertion */

    /* adding role admin to this register user and
        set permission to manage all user to this user
    */
    $role_admin =  Role::where('name', '=', 'admin')->first();
    $permission_admin = Permission::where('name','manage-all-users')->first();
    $Permission_upload = Permission::where('name','upload-sound-files')->first();

    $new_user->attachRole($role_admin->id);
    //$new_user->roles()->attach($role_admin->id); // id only

    // Permission need to be attach with Role Object
    // $role_admin->attachPermission($permission_admin->id);
    // $role_admin->attachPermissions(array($permission_admin->id, $Permission_upload->id));

    // dd($new_user);
    // dd($role_admin);
    dd($request);
    //var_dump($request);
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
    echo "list of users";
    $all_user = new User::where('name', '!=', 'admin')->all();
    //return()
    dd($all_user);
  }



}
