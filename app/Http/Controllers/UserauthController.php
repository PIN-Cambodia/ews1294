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
  public function loginauth(Request $request)
  {
    // dump the given variable and end execution of the script
    //dd($request->_token);
    if(Auth::attempt(['name'=> $request->username, 'password' => $request->password]))
    {
      echo "auth check <br>";
      // redirect to upload sound file page
      // return redirect()->intended('any url link');
    }
    else {
      echo "else auth check <br>";
      // redirct to the back to the login form

    }

    //dd($request->username);
    echo "username= " . $request->username;
    echo "password= " . $request->password;
    //var_dump($request);
  }

  public function register(Request $request)
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
}
