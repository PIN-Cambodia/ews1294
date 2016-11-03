@extends('layouts.master')
@section('content')
<!-- Services Section -->
<section id="register">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('auth.register') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-heading">{{ trans('auth.register') }}</div>
          <div class="panel-body">
              <form class="form-horizontal" role="form" method="POST" action="{{ url('/register') }}">
                  {{ csrf_field() }}
                  {{ old('name') }}
                  <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                      <label for="name" class="col-md-4 control-label">{{ trans('auth.name') }}</label>
                      <div class="col-md-6">
                          <input id="name" type="text" class="form-control" name="name">
                          @if ($errors->has('name'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('name') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>

                  <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                      <label for="email" class="col-md-4 control-label">{{ trans('auth.email') }}</label>
                      <div class="col-md-6">
                          <input id="email" type="email" class="form-control" name="email">
                          @if ($errors->has('email'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('email') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>

                  <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                      <label for="password" class="col-md-4 control-label">{{ trans('auth.password') }}</label>
                      <div class="col-md-6">
                          <input id="password" type="password" class="form-control" name="password">
                          @if ($errors->has('password'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('password') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>

                  <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                      <label for="password-confirm" class="col-md-4 control-label">{{ trans('auth.confirm_password')}}</label>
                      <div class="col-md-6">
                          <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                          @if ($errors->has('password_confirmation'))
                              <span class="help-block">
                                  <strong>{{ $errors->first('password_confirmation') }}</strong>
                              </span>
                          @endif
                      </div>
                  </div>
                  <div class="form-group">
                      <label for="user_role_type" class="col-md-4 control-label">{{ trans('auth.type_of_user_role')}}</label>
                      <div class="col-md-6">
                          <select name="user_role_type" class="form-control" id="user_role_type">
                            @role('admin')
                              <option value="0">{{ trans('auth.select_user_role')}}</option>
                              <option value="1"> NCDM </option>
                              <option value="2"> PCDM </option>
                            @endrole
                            @role('NCDM')
                              <option value="2"> PCDM </option>
                            @endrole
                          </select>
                      </div>
                  </div>
                  <div class="form-group" id="pcdm_authorized_province"></div>

                  <div class="form-group">
                      <div class="col-md-6 col-md-offset-4">
                          <button type="submit" class="btn btn-primary">
                              <i class="fa fa-btn fa-user"></i> {{ trans('auth.register') }}
                          </button>
                      </div>
                  </div>
              </form>
          </div><!--/.panel panel-body-->
      </div><!--/.panel panel-default-->
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->

    <script>
        // global csrf token variable
        var token = $('input[name=_token]').val();

        $(document).ready(function() {
            $("#pcdm_authorized_province").hide();
        });

        /* Edit User Profile */
        $(document).on('change', '#user_role_type', function()
        {
            var option_value = $("#user_role_type").val();
            // if option value is select option or NCDM then hide pcdm authorized province
            if(option_value == 1 || option_value == 0)
            {
                $("#pcdm_authorized_province").hide();
            }
            // if the user is PCDM, then show list of province
            if(option_value == 2)
            {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/get_authorized_province') }}",
                    data: {_token: token},
                    cache: false,
                    success: function(result)
                    {
                        $("#pcdm_authorized_province").html(result).show();
                    }
                });
            }

            return false;
        });
    </script>
</section>
@endsection
