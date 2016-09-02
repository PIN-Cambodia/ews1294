@extends('layouts.master')
@section('content')
<section id="reset">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('auth.reset') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading"> {{ trans('auth.reset') }} </div>
          <div class="panel-body">
              <form class="form-horizontal" role="form" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                  
              </form>
            </div><!-- \ panel panel-body -->
          </div><!-- \ panel panel-default -->
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>

@endsection
