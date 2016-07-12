<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
/* Calling user model to be used */
use App\User;

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

    /* insert registration data into table users in databaase */
    $new_user = new User;
    $new_user -> name = $request->name;
    $new_user -> email = $request->email;
    $new_user -> password = Hash::make($request -> password);
    // $new_user -> created_at = date("Y-m-d H:i:s");
    // $new_user -> updated_at = date("Y-m-d H:i:s");
    $new_user -> save();
    /* end of insertion */

    dd($new_user);
    //dd($request);
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
  }



}
