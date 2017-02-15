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
        <div class="panel panel-default">
          <div class="panel-heading" ><center><b>{{ trans('menus.about_us') }} </b></center> </div>
            <br />
            <div class="row">
         
                <div class="col-md-2 col-lg-2"></div>
                <div class="col-md-8 col-lg-8">
                <p style="text-align: justify;" >
                    {{ trans('pages.content1')}}
                </p> 
                <p style="text-align: justify;" >
                    {{ trans('pages.content2')}}
                </p> 
                <p style="text-align: justify;" >
                    {{ trans('pages.content3')}}
                </p> 
                </div>
               
                <div class="col-md-2 col-lg-2"></div>
                <img src="/about_ews.jpg" height="50px">
   
            </div><!-- \ panel panel-body -->
        </div><!-- \ panel panel-default -->
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>

@endsection
