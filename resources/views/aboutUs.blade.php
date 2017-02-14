@extends('layouts.master')
@section('content')
<section id="login">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="pe-7s-users pe-lg"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('menus.about_us') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading"><center>{{ trans('menus.about_us') }} </center> </div>
            <br />
            <div class="row">
         
                <div class="col-md-2 col-lg-2"></div>
                <div class="col-md-8 col-lg-8">
                	{{ trans('pages.about_ews')}}
                </div>
               
                <div class="col-md-2 col-lg-2"></div>
   
            </div><!-- \ panel panel-body -->
        </div><!-- \ panel panel-default -->
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>

@endsection
