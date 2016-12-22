@extends('layouts.master')

@section('content')
<!-- Services Section -->
<section id="services">
  <!-- Opening a form -->
  @if(Session::has('message'))
  <p class="alert-danger">{{Session::get('message')}}</p>
  @endif

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      <div class="row">
        <ol class="breadcrumb">
          <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
          <li class="active"> EWS API</li>
        </ol>
      </div><!--/.row-->

        <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-xs-11 col-md-11 col-lg-11">
                  <div class="row topspace">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                      <h4><b>Register New Contact </b></h4>
                    </div>
                  </div>
                  <div class="row topspace">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                      <b>HTTP POST /api/v1/register_new_contact?api_token=&lt;token&gt;</b><br />
                      This API adds new contact with mobile number and commune code into EWS system. When villager calls to EWS number for
                        registration, this API must be called by passing neccessary parameters as the following:<br>
                      <b>- api_token</b> : should be requested from EWS Admin. An authorized token should be passed,
                        otherwise this API could not be accessible.<br />
                    </div>
                  </div>
                </div>
              </div><br />
                <!-- Sensor API -->
                <div class="row">
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <h4><b>Receive data from sensor </b> </h4>
                    </div>
                    <div class="col-xs-12 col-md-12 col-lg-12">
                        <br><b>HTTP POST /api/v1/sensorapi?api_token=&lt;token>&data={"sensorId":integer value,"streamHeight":"value","charging":"value","voltage":"value","timestamp":"value"}</b><br />
                        This API receives data from sensor such as water level, voltage, charging status and timestamp to EWS system. Relevant officers or people in the affected communes will receive the call
                        if the stream height value of the sensor reaches the specified warning or emergency level. <br>
                        API requires the following parameters: <br>
                        <b>- api_token</b> : should be requested from EWS Admin. Only authorized token can access the API.<br />
                        <b>- data </b> : is the sensor data to be sent. This string is in JSON format. <br />
                    </div>
                </div>
            </div><!-- \ panel panel-body -->
        </div><!-- \ panel panel-default -->
    </div>	<!--/.main-->
</section>
@endsection
