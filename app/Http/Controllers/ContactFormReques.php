<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContactFormRequest extends Request {

  public function authorize()
  {
    return false;
  }

  public function rules()
  {
    return [
    'name' => 'required',
    'email' => 'required|email',
    'message' => 'required',
      //
    ];
  }

}