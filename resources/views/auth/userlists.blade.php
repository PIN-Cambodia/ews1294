@extends('layouts.master')
@section('content')
<section id="userlist">
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
            <div class="fixed-panel">
              {{ csrf_field() }}
              @if(!empty($userlists))
                @foreach ($userlists as $userlist)
                  <div class="col-xs-12 col-md-4 col-lg-4">
                    <div class="row">
                      <div class="col-xs-2 col-md-2 col-lg-2">
                        <i class="fa fa-user fa-lg" aria-hidden="true"></i>
                      </div>
                      <div class="col-xs-10 col-md-10 col-lg-10">
                        <div class="row" style="margin-top: 5px; margin-bottom: 5px;">
                          <div class="col-xs-12 col-md-12 col-lg-12">
                            @if($userlist->is_delete == 1)
                              <i class="fa fa-trash-o fa-lg" aria-hidden="true" style="color: #914854;"></i>
                            @endif
                            {{ $userlist->name }}
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-xs-12 col-md-12 col-lg-12">
                            <!-- Edit User Profile Button -->
                            <button class="btn btn-info" id="edit_user_profile" name="{{ $userlist->id }}">
                              <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                              {{ trans('auth.edit') }} </button>

                            <!-- Enable or Disable user Button -->
                            <!-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/enabledisable') }}">
                            {{ csrf_field() }} -->
                              @if($userlist->is_disable == 1)
                                <button class="btn btn-primary" id="enable_disble_user" name="enable_user" value="{{ $userlist->id }}" >
                                  <i class="fa fa-eye fa-lg" aria-hidden="true"></i>
                                  {{ trans('auth.enable') }}
                                </button>
                              @else
                                <button class="btn btn-primary" id="enable_disble_user" name="disable_user" value="{{ $userlist->id }}" >
                                  <i class="fa fa-eye-slash fa-lg" aria-hidden="true"></i>
                                  {{ trans('auth.disable') }}
                                </button>
                            @endif
                            <!-- </form> -->
                              <!-- Delete a user Button -->
                              <button class="btn btn-danger" id="delete_user" name="{{ $userlist->id }}">
                                <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                {{ trans('auth.delete') }}</button>
                            </div>
                        </div>
                      </div><!-- / col -->
                    </div><!-- / row -->
                  </div>
                @endforeach
              @endif
            </div> <!-- /fixed-panel -->
          </div><!-- /panel-body -->
        </div><!-- /panel-default -->
      </div> <!-- / col -->
    </div><!--/.row-->
  </div>	<!--/.main-->

  <!--show waiting loading dialog -->
  <div class="modal fade" id="modal_loading_waiting" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-center">
    	  <div><h5>{{ trans('auth.loading_await') }}</h5></div>
        <div id="waiting"></div>
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
        <span id='profile_content'></span>

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
          <button type="button" class="btn btn-danger" id="btn_delete_yes">
              <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
               {{ trans('auth.delete') }}</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

  <script>
  // global csrf token variable
  var token = $('input[name=_token]').val();

  /* Edit User Profile */
  $(document).on('click', '#edit_user_profile', function()
  {
    var btn_val = $(this).attr('name');
    $.ajax({
  		type: "POST",
  		url: "{{ url('/userprofile') }}",
      data: {_token: token, uid: btn_val},
  		cache: false,
  		success: function(result)
  		{
        // alert("success= " + result[0].id);
  			$("#profile_content").html(result).show();
  			$('#modal_user_profile').modal('show');
  		},
      // error: function() {
      //   alert('sorry, data cannot be fetch');
      // }
  	});
  	return false;
  });
  /* Save Edited data of a user */
  $(document).on('click', '#save_user_data', function()
  {
    var txt_user_name = $('#txt_user_name').val();
    var txt_user_email = $('#txt_user_email').val();
    var btn_val = $(this).attr('name');
    //$('#modal_user_profile').modal('hide');
    //alert('save= ' + btn_user_val + " name= " + txt_user_name + " email= " + txt_user_email);
    $.ajax({
  		type: "POST",
  		url: "{{ url('/saveuserdata') }}",
      data: {_token: token, uid: btn_val, uname: txt_user_name, uemail: txt_user_email},
  		cache: false,
  		success: function(result)
  		{
          // alert("success= " + result[0].id);
  			$("#profile_content").html(result).show();
            location.reload();
  			//$('#modal_user_profile').modal('show');
  		},
      // error: function() {
      //   alert('sorry, data cannot be fetch');
      // }
  	});
  	return false;

  });
  /* Enable or Disable a User */
  $(document).on('click', '#enable_disble_user', function()
  {
      var btn_name = $(this).attr('name');
      var btn_value = $(this).attr('value');
      $.ajax({
    		type: "POST",
    		url: "{{ url('/enabledisable') }}",
        data: {_token: token, btn_name: btn_name, btn_value: btn_value },
    		cache: false,
    		success: function(result)
    		{
                location.reload();
    		},
        // error: function() {
        //   alert('sorry, data cannot be fetch');
        // }
    	});
    	return false;

  });

  /* Delete User */
  $(document).on('click', '#delete_user', function()
  {
      // alert("delete");
      var btn_delete_val = $(this).attr('name');
      $('#modal_delete_user').modal('show');
      $('#btn_delete_yes').click(function(e){
        //alert('delete=yes=> ' + btn_delete_val);
        $.ajax({
    			type: "POST",
    			url: "{{ url('/deleteuser') }}",
    			data: {_token: token, delete_val: btn_delete_val},
    			cache: false,
    			success: function(result)
    			{
    				//$('#modal_DeleteEach').modal('hide');
    				// reload page
    				location.reload();
    			}
    		});
    		return false;
    	});
    	return false;
  });


  </script>
</section>


@endsection
