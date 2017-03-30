<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactFormRequest;
use App\Post;
use Illuminate\Support\Facades\Session;
use Config;

Class ContactController extends Controller
{
         public  function getContact(){
        return view('contactUs');

    }
    
    public function postContact(ContactFormRequest $request){
      require 'Mailer/Sendemail.php';

        // $this->validate($request,[
        //     'email'=> 'required|email',
        //     'name' => 'required',
        //     'message' => 'required']);
        $data = array(
            'email'=> $request->get('email'),
            'name'=> $request->get('name'),
            'user_message'=> $request->get('message')

            );

  //    Mail::send('contactUs', $data, function($messages) use ($data){
  //       $messages->from($data['email'],$data['name']);
  //       $messages->to('chenda.loeurt@gmail.com');
  //       $messages->subject('hello world');
        

  //    });

   
        $org =$data['email'];
        $email = "chenda.loeurt@gmail.com";
        $title = "Contact from EWS";
        $body = $data['user_message'];
        $fname = $data['name'];
       

        $send = Sendemail($org, $email, $fname, $title, $body);
        if ($send) {
           
        }else{
           return redirect('contact');
           Session::flash('success','Your Email was Sent!');
        }
 
    
}
}
