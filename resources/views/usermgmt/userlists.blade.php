@extends('layouts.master')
@section('content')
<section id="services">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('menus.users') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12 ">
        <div class="panel panel-default">
          <div class="panel-heading">{{ trans('menus.users') }}</div>
          <div class="panel-body">
            <!-- Opening a form -->
            <!-- {!! Form::open(array('route' =>'auth.login', 'method'=>'post', 'class'=>'form-horizontal')) !!} -->
            @foreach ($userlists as $userlist)
              <div class="col-xs-12 col-md-4 col-lg-4">
                <div class="row">
                  <div class="col-xs-2 col-md-2 col-lg-2">
                    <i class="fa fa-user fa-lg" aria-hidden="true"></i>
                  </div>
                  <div class="col-xs-10 col-md-10 col-lg-10">
                    {{ $userlist->name }} <br />
                    <button class="btn btn-info">
                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                         Edit </button>
                    <button class="btn btn-primary">
                        <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                         Enable </button>
                    <button class="btn btn-danger">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                         Delete </button>
                  </div>
                </div><!-- / row -->
              </div>
            @endforeach
            <!-- closing form -->
            <!-- {!! Form::close() !!} -->
          </div><!-- /panel-body -->
        </div><!-- /panel-default -->
      </div> <!-- / col -->
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>


@endsection
