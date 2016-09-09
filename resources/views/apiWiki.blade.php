@extends('layouts.master')

@section('content')
<!-- Services Section -->
<section id="services">
  <!-- Opening a form -->
  @if(Session::has('message'))
  <p class="alert-danger">{{Session::get('message')}}</p>
  @endif
  <!-- {!! Form::open(array('route' =>'call.them', 'method'=>'post','id'=>'uploadForm')) !!} -->
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
      <li class="active"> EWS API</li>
    </ol>
  </div><!--/.row-->
  <div class="row topspace">
    <div class="col-xs-11 col-md-11 col-lg-11">
      <div class="row topspace">
        <div class="col-xs-12 col-md-12 col-lg-12">
          <h4>Register New Contact </h4>
        </div>
         <!-- <div class="col-xs-3 col-md-3 col-lg-3"> -->


        <!-- </div> -->
      </div>

      <div class="row topspace">
        <div class="col-xs-12 col-md-12 col-lg-12">
          <b>HTTP GET /api/v1/register_new_contact?api_token=&lt;token&gt;&phone=&lt;phone_number&gt;&commune=&lt;commune_code&gt;</b><br />

          This API adds new contact into EWS system. When villager calls to EWS number for registration, this API must be called by passing neccessary parameters as the following:<br>
          <b>- api_token</b> : should be requested from EWS Admin. An authorized token should be passed, otherwise this API could not be accessible.<br />
          <b>- phone</b> : is the phone number of caller.<br />
          <b>- commune</b> : is the commune code in which the caller is living.<br />
          It will return  a string "Successfully inserted" , if the transaction is successful, otherwise "Fail to insert".

        </div>
        <!-- <div class="col-xs-9 col-md-9 col-lg-9">
          <input type="file" name="soundFile" id="soundFile"><br /> -->
          <!-- <input type="file" name="phoneContactFile" id="phoneContactFile" class="hideContactFile"> -->
        <!-- </div> -->
      </div>

    </div>

      <div class="row topspace rg">
          <div class="col-xs-12 col-md-12 col-lg-12" id="numberOfPhones">

          </div>
      </div>

    </div>
  </div><!--/.row-->

</div>	<!--/.main-->
<!-- closing form -->
<!-- {!! Form::close() !!} -->
</section>

@endsection
