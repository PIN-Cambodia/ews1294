<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\Post;
use Illuminate\Support\Facades\Session;
use Config;

Class ContactController extends Controller
{
         public  function getContact(){
        return view('contactUs');

    }

    public function postContact(Request $request){
       

        $this->validate($request,[
            'email'=> 'required|email',
            'name' => 'required',
            'message' => 'required']);
        // $data = array(
        //     'email'=> $request->email,
        //     'name'=> $request->name,
        //     'user_message'=> $request->message

        //     );

     // Mail::send('contactUs', $data, function($messages) use ($data){
     //    $messages->from($data['email']);
     //    $messages->to('vcgroup3laravel@gmail.com');
     //    $messages->subject($data['user_message']);

     // });
//          \Mail::send('contactUs',
//         array(
//             'name' => $request->get('name'),
//             'email' => $request->get('email'),
//             'user_message' => $request->get('message')
//         ), function($messages)
//     {
//         $messages->from('chenda.loeurt@gmail.com');
//         $messages->to('.com', 'Admin')->subject('TODOParrot Feedback');
//     });

//   return \Redirect::route('contact')->with('message', 'Thanks for contacting us!');

// }


        
        $org = "ews";
        $email = "chenda.loeurt@gmail.com";
        $title = "Test email";
        $body = "Hello World!";
        $fname = "Chenda Loeurt";

        $send = Sendemail($org, $email, $fname, $title, $body);
        if ($send) {
            return redirect('contactUs')->with('message','you have successful contact us');
        }else{
            echo "Email could not send!";
        }
   // Session::flash('success','Your Email was Sent!');
    
}
}
