<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


Class ContactController extends Controller
{
         public  function getContact(){
        return view('contactUs');

    }

    Public function postContact(Request $request){
        $this->validate($request,[
            'email'=> 'required|email',
            'name' => 'required',
            'message' => 'required']);
        $data = array(
            'email'=> $request->email,
            'name'=> $request->name,
            'message'=> $request->message

            );

     Mail::send('contactUs', $data, function($messages) use ($data){
        $messages->from($data['email']);
        $messages->to('chenda.louert@gmail.com');
        $messages->subject($data['message']);

     });
    Session::flash('success','Your Email was Sent!');
    return redirect('contactUs');



}
 
}
