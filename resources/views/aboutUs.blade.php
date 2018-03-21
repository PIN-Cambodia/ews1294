@extends('layouts.master')
@section('content')
<section>

  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
         <i class="pe-7s-users pe-lg"></i> {{ trans('menus.about_us') }}
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default" >
         <!--  <div class="panel-heading" ><center><b>{{ trans('menus.about_us') }} </b></center> </div> -->
         <!-- <div style="height: 66px;"></div> -->
        <!--     <br /> -->
           <div id="div" style="overflow-x: hidden; overflow-y:auto;height:auto;max-height:500px;">
           <div><center><img src="/about_EWS.jpg" style="height:45%; width:82%"></centerb></div>
           <br>

            <div class="row"> 
                <div class="col-md-1 col-lg-1"></div>
                <div class="col-md-10 col-lg-10">
                <p style="font-family: Lato; font-size: 17px; text-align: justify;" >
                    {{ trans('pages.content1')}}
                </p> 
                <p style=" font-family: Lato; font-size: 17px; text-align: justify;" >
                    {{ trans('pages.content2')}}
                </p> 
                <p style="font-family: Lato; font-size: 17px; text-align: justify;" >
                    {{ trans('pages.content3')}}
                </p>         
                </div>  
                <div class="col-md-1 col-lg-1"></div>
              </div>
              <br>
            <div class="row">
            <div class="col-ms-3 col-md-3 col-lg-3"></div>
            <div class="col-sm-2 col-md-2 col-lg-2">
              <a href="http://ec.europa.eu/echo/what/humanitarian-aid/risk-reduction_en" target="_blank">
                <img src="/EU.png" height=auto; width="65%";>
              </a>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 text-center">
              <a href="https://www.facebook.com/PINCambodiacz/" target="_blank">
                <img src="/PIN.png" height=auto; width="60%";>
              </a>
            </div>
            <div class="col-sm-2 col-md-2 col-lg-2 text-center">
              <a href="http://www.ncdm.gov.kh/" target="_blank">
                <img src="/NCDM.png" height=auto; width="50%";>
              </a>
            </div>
            <div class="col-ms-3 col-md-3 col-lg-3"></div>
            </div>
            </div><!-- \ panel panel-body -->
        </div><!-- \ panel panel-default -->
      </div>
    </div><!--/.row-->
  </div>  <!--/.main-->
<script>
  $(document).ready(function() {
    fitIframe();
    $(window).resize(function() {
      fitIframe();
    });
    
  });
  function fitIframe() {
    /* $('#sidebar-collapse').height()-90 : means take height of sidebar-collapse - the height of header and footer  */
    $('#div').css("min-height", $('#sidebar-collapse').height()-(50+$('#footer').height()));
  }
</script>
</section>

@endsection

