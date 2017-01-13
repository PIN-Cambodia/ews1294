@extends('layouts.master')
@section('content')
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
                    {{ csrf_field() }}
                    <div class="row" id="report_header_content">
                        <div class="col-lg-5">
                            <div class="col-xs-4 col-md-4 col-lg-4" style="padding-top:10px; text-align: right;">
                                {{ trans('pages.province_:') }}
                            </div>
                            <div class="col-xs-8 col-md-8 col-lg-8">
                                <select class="select_style" name="province_id" id="province_id">
                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
                                        <option value="0"> {{ trans('pages.select_province') }} </option>
                                    @endif
                                    @if(!empty($allprovince))
                                        @foreach ($allprovince as $item)
                                            @if (App::getLocale()=='km')
                                                <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE_KH }}</option>
                                            @else
                                                <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE }}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-7" id="sensor_list_div">
                            <div class="col-xs-4 col-md-4 col-lg-4" style="padding-top:10px; text-align: right;">
                                {{ trans('sensors.sensor_id_:') }}
                            </div>
                            <div class="col-xs-8 col-md-8 col-lg-8">
                                <select class="select_style" name="sensor_list" id="sensor_list">

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row topspace" style="text-align:center">
                            <div class="col-xs-12 col-md-12 col-lg-12" >
                                <button class="btn btn-primary" name="submit_report" id="submit_report">
                                    <i class="fa fa-send fa-lg" aria-hidden="true"></i>
                                    {{ trans('pages.show_data') }}
                                </button>
                                <button class="btn btn-danger" id="reset_page">
                                    <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                                    {{ trans('pages.reset') }}
                                </button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <table class='table table-striped responsive' id='calllog_tbl' cellspacing='0' width='100%'>
                        <thead>
                        <tr>
                            <th rowspan='2' class='text-center'> {{ trans('pages.tbl_title_number') }} </th>
                            <th rowspan='2' class='text-center'> {{ trans('pages.tbl_title_date') }} </th>
                            <th rowspan='2' class='text-center'> {{ trans('pages.tbl_title_sound_file') }} </th>
                            <th rowspan='2' class='text-center'> {{ trans('pages.tbl_title_list_of_communes') }} </th>
                            <th rowspan='2' class='text-center'> {{ trans('pages.tbl_no_of_phone_called') }} </th>
                            <th colspan='6' class='text-center'> {{ trans('pages.tbl_title_call_status') }} </th>
                        </tr>
                        <tr>
                            <th class='text-center'> {{ trans('pages.tbl_title_completed') }} </th>
                            <th class='text-center'> {{ trans('pages.tbl_title_failed') }} </th>
                            <th class='text-center'> {{ trans('pages.tbl_title_busy') }} </th>
                            <th class='text-center'> {{ trans('pages.tbl_title_no_answer') }} </th>
                            <th class='text-center'> {{ trans('pages.tbl_title_error') }} </th>
                            <th class='text-center'> {{ trans('pages.tbl_title_total') }} </th>
                        </tr>
                        </thead>
                    </table>
                </div><!-- \ panel panel-body -->
            </div><!-- \ panel panel-default -->
        </div><!--/.col-->
    </div><!--/.row-->
</div>	<!--/.main-->

<script>
    $(function(){
        // global csrf token variable
        token = $('input[name=_token]').val();
        var button = $('#submit_report');

        $('#sensor_list_div').hide();
        button.removeAttr('disabled');

        $(document).on('click', '#reset_page', function()
        {
            $(location).attr("href", '/calllogreport');
        });

        /* Submit Report */
        $(document).on('click', '#submit_report', function()
        {
            var sensor_val = $('#sensor_val').val();
            // once submit_report button is clicked, disabled it for 3 seconds to prevent multiple double click
            button.attr('disabled', 'disabled');
            setTimeout(function() {
                button.removeAttr('disabled');
            },3000);

            var province_val = $('#province_id').val();
            var sensor_id = $('#sensor_list').val();
            if(province_val!=0)
            {
                $('#calllog_tbl').DataTable( {
                    destroy: true,
                    "ajax": {
                        type: "POST",
                        url: "{{ url('/getCallLogReport') }}",
                        data: {_token: token, prov_id: province_val, sensor_id: sensor_id},
                        cache: false,
                        dataSrc: 'data'
                    },
                    "columns": [
                        { "data": "No." },
                        { "data": "created_at" },
                        { "data": "sound_file" },
                        { "data": "commune_name_all" },
                        { "data": "no_of_phones_called" },
                        { "data": "success_call" },
                        { "data": "failed_call" },
                        { "data": "busy_call" },
                        { "data": "no_answer_call" },
                        { "data": "error_number_call" },
                        { "data": "current_total_call" }
                    ],
                    scrollY: '35vh',
                    deferRender:    true,
                    scroller:       true
                } );
            }
            $("#calllog_tbl").show();
            return false;
        });
    });

    // get sensor list of each province
    $('#province_id').change(function () {
        var province_id_val = $(this).val();
        if(province_id_val != '')
        {
            $.ajax({
                type: 'POST',
                url: '/getSSList',
                data: {_token: token, province_id_val: province_id_val},
                cache: false,
                success: function(result)
                {
                    // if there is no sensor in that province then select option of sensor list is not shown
                    if(result!="")
                    {
                        $('#sensor_list').html(result);
                        $('#sensor_list_div').show();
                    }
                    else $('#sensor_list_div').hide();
                }
            });
        }
        return false;
    });
</script>
@endsection
