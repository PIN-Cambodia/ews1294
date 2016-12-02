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
                        <div class="row">
                            <div class="col-lg-12">
                                <table class='table table-bordered'>
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
                                        <th class='text-center active'> {{ trans('pages.tbl_title_total') }} </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @if(!empty($result))
                                            <tr>
                                                <td class='text-center'> {{ $result["No"] }} </td>
                                                <td class='text-center'> {{ $result["created_at"] }} </td>
                                                <td> {{ $result["sound_file"] }} </td>
                                                <td> {{ $result["commune_name_all"] }} </td>
                                                <td class='text-center'> {{ $result["no_of_phones_called"] }} </td>
                                                <td class='text-center'> {{ $result["success_call"] }} </td>
                                                <td class='text-center'> {{ $result["failed_call"] }} </td>
                                                <td class='text-center'> {{ $result["busy_call"] }} </td>
                                                <td class='text-center'> {{ $result["no_answer_call"] }} </td>
                                                <td class='text-center'> {{ $result["current_total_call"] }} </td>
                                            </tr>

                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div><!-- \ panel panel-body -->
                </div><!-- \ panel panel-default -->
            </div>
        </div><!--/.row-->
    </div>	<!--/.main-->
@endsection
