<?php
 namespace App\Http\Controllers;

class AboutController extends Controller {

    public function create()
    {
        return view('/contactUs');
    }

    
  public function store(ContactFormRequest $request)
    {
    	return \Redirect::route('contact')
      ->with('message', 'Thanks for contacting us!');
    }

}


 ?>