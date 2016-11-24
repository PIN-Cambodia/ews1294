@extends('layouts.master')
@section('content')
<!-- Services Section -->
<section id="calllogreport">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('menus.calllog_report') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <div class="panel panel-default">
          <div class="panel-body">
              {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/getCallLogReport') }}">--}}
                 {{ csrf_field() }}
                <div class="row">
                 <div class="col-xs-4 col-md-4 col-lg-4">
                     {{ trans('pages.province_:') }}
                 </div>
                 <div class="col-xs-8 col-md-8 col-lg-8">
                     <select name="province_id" id="province_id">
                         <option value="0"> {{ trans('pages.select_province') }} </option>
                         @foreach ($allprovince as $item)
                             @if (App::getLocale()=='km')
                                 <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE_KH }}</option>
                             @else
                                 <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE }}</option>
                             @endif
                         @endforeach
                     </select>
                 </div>
                </div>
                <div class="form-group">
                    <div class="row topspace" style="text-align:center">
                        <div class="col-xs-12 col-md-12 col-lg-12" >
                            <button class="btn btn-primary" name="submit_report" id="submit_report">
                                <i class="fa fa-send fa-lg" aria-hidden="true"></i>
                                {{ trans('pages.show_data') }}
                            </button>
                            <button type="reset" class="btn btn-danger">
                                <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                                {{ trans('pages.reset') }}
                            </button>
                        </div>
                    </div>
                </div>
                <hr>
                <div id="report_result" class="table-responsive" style="max-height: 500px; overflow-y: scroll;padding-bottom: 5px;"></div>
             {{-- </form>--}}
            </div><!-- \ panel panel-body -->
          </div><!-- \ panel panel-default -->
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->
</section>

<script>
    // global csrf token variable
    var token = $('input[name=_token]').val();
    //$("#report_result").hide();

    /* Edit User Profile */
    $(document).on('click', '#submit_report', function()
    {
        var province_val = $('#province_id').val();
        if(province_val!=0)
        {
            $.ajax({
             type: "POST",
             url: "{{ url('/getCallLogReport') }}",
             data: {_token: token, prov_id: province_val},
             cache: false,
             success: function(result)
             {
                //alert("success= " + result);
                $("#report_result").html(result).show();
             },
             error: function() {
                alert('sorry, data cannot be fetch');
             },
            always: function(alwaysD) {
                 console.log(alwaysD);
            }
             });
        }

        return false;
    });
</script>
@endsection
