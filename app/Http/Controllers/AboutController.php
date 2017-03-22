<?php
 namespace App\Http\Controllers;
 use Illuminate\Http\Request\ContactFormRequest;

class AboutController extends Controller {

    public function create()
    {
        return view('/contactUs');
    }

    
  public function store(ContactFormRequest $request)
    {


    \Mail::send('emails.contact',
        array(
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'user_message' => $request->get('message')
        ), function($message)
    {
        $message->from('chenda.loeurt@gamil.com');
        $message->to('chenda.loeurt@gamil.com', 'Admin')->subject('Feedback');
    });
    	return \Redirect::route('contact')
      ->with('message', 'Thanks for contacting us!');
    }

}


 ?>