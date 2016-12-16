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
                    <div class="fixed-panel">
                        {{--<form class="form-horizontal" role="form" method="POST" action="{{ url('/calllogreport') }}">--}}
                        {{ csrf_field() }}
                        <div class="row" id="report_header_content">
                            <div class="col-xs-4 col-md-4 col-lg-4">
                                {{ trans('pages.province_:') }}
                            </div>
                            <div class="col-xs-8 col-md-8 col-lg-8">
                                <select name="province_id" id="province_id">
                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
                                        <option value="0"> {{ trans('pages.select_province') }} </option>
                                    @endif

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
                                    <button class="btn btn-danger" id="reset_page">
                                        <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                                        {{ trans('pages.reset') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        {{--<div id="report_result" class="table-responsive" style="max-height: 550px; overflow-y: scroll;padding-bottom: 5px;"></div>--}}
                        <table class='table responsive cell-border' id='calllog_tbl' cellspacing='0' width='100%'>
                            <thead>
                            <tr>
                                <th rowspan='2' class='text-center active'> {{ trans('pages.tbl_title_number') }} </th>
                                <th rowspan='2' class='text-center active'> {{ trans('pages.tbl_title_date') }} </th>
                                <th rowspan='2' class='text-center active'> {{ trans('pages.tbl_title_sound_file') }} </th>
                                <th rowspan='2' class='text-center active'> {{ trans('pages.tbl_title_list_of_communes') }} </th>
                                <th rowspan='2' class='text-center active'> {{ trans('pages.tbl_no_of_phone_called') }} </th>
                                <th colspan='5' class='text-center active'> {{ trans('pages.tbl_title_call_status') }} </th>
                            </tr>
                            <tr>
                                <th class='text-center active'> {{ trans('pages.tbl_title_completed') }} </th>
                                <th class='text-center active'> {{ trans('pages.tbl_title_failed') }} </th>
                                <th class='text-center active'> {{ trans('pages.tbl_title_busy') }} </th>
                                <th class='text-center active'> {{ trans('pages.tbl_title_no_answer') }} </th>
                                <th class='text-center active'> {{ trans('pages.tbl_title_wrong_number') }} </th>
                                <th class='text-center active'> {{ trans('pages.tbl_title_total') }} </th>
                            </tr>
                            </thead>
                        </table>
                        {{-- </form>--}}
                    </div> <!-- /fixed-panel -->
                </div><!-- \ panel panel-body -->

            </div><!-- \ panel panel-default -->
        </div><!--/.col-->
    </div><!--/.row-->
</div>	<!--/.main-->

<script>
    $(function(){
        // global csrf token variable
        var token = $('input[name=_token]').val();
        var button = $('#submit_report');
        button.removeAttr('disabled');

        $(document).on('click', '#reset_page', function()
        {
            $(location).attr("href", '/calllogreport');
        });

        /* Submit Report */
        $(document).on('click', '#submit_report', function()
        {
            // once submit_report button is clicked, disabled it for 3 seconds to prevent muliple double click
            button.attr('disabled', 'disabled');
            setTimeout(function() {
                button.removeAttr('disabled');
            },3000);

            var province_val = $('#province_id').val();
            if(province_val!=0)
            {
                $('#calllog_tbl').DataTable( {
                    destroy: true,
                    "ajax": {
                        type: "POST",
                        url: "{{ url('/getCallLogReport') }}",
                        data: {_token: token, prov_id: province_val},
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
                        { "data": "wrong_number_call" },
                        { "data": "current_total_call" }
                    ],
                    scrollY:        true,
                    deferRender:    true,
                    scroller:       true
                } );
            }
            $("#calllog_tbl").show();
            return false;
        });
    });
</script>
@endsection
