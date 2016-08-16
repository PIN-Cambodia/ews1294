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
                    <button class="btn btn-info" id="{{ $userlist->id }}" data-toggle="modal" data-target="#modal_user_profile">
                        <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                         {{ trans('auth.edit') }} </button>
                    <button class="btn btn-primary" id="{{ $userlist->id }}" >
                        <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                         {{ trans('auth.enable') }} </button>
                    <button class="btn btn-danger" id="{{ $userlist->id }}" data-toggle="modal" data-target="#modal_delete_user">
                        <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                         {{ trans('auth.delete') }}</button>
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


  <!--show waiting loading dialog -->
  <div class="modal fade" id="modal_loading_waiting" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-center">
    	  <div><h5>{{ trans('auth.loading_await') }}</h5></div>
        <div id="waiting1"></div>
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- Edit User Profile Modal -->
  <div class="modal fade" id="modal_user_profile" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">{{ trans('auth.user_profile') }}</h4>
        </div>
        <div class="modal-body">

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <i class="fa fa-times fa-lg" aria-hidden="true"></i>
            {{ trans('auth.cancel') }}
          </button>
          <button type="button" class="btn btn-primary">
            <i class="fa fa-floppy-o fa-lg" aria-hidden="true"></i>
            {{ trans('auth.save') }}
          </button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <!-- show confirm delete dialog -->
  <div class="modal fade" id="modal_delete_user" data-backdrop="static" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h3 class="modal-title"><b>{{ trans('auth.dialog_confirm') }}</b></h3>
        </div>
        <div class="modal-body">
           <h4>
             {{ trans('auth.action_confirmation_question') }} <br /><br />
             {{ trans('auth.action_confirmation_yes') }} <br />
             {{ trans('auth.action_confirmation_no') }} <br /><br />
           </h4>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss='modal'>
              <i class="fa fa-times fa-lg" aria-hidden="true"></i>
               {{ trans('auth.cancel') }} </button>
          <button type="button" class="btn btn-danger">
              <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
               {{ trans('auth.delete') }}</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <script >
  $('#myModal').on('shown.bs.modal', function () {
    $('#myInput').focus()
    })
  </script>
</section>


@endsection
