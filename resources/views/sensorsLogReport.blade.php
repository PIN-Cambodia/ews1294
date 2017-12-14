@extends('layouts.master')

@section('datatable-css')
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet" />
@endsection

@section('datatable-js')
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

@endsection
@section('content')

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active"> {{ trans('sensors.sensorLogReport') }} </li>
        </ol>
    </div><!--/.row-->
    <div class="row" >
        <div class="col-xs-12 col-md-12 col-lg-12" >
                <div class="panel panel-default">
                    <div class="panel-heading text-center " style="height: 10%;">
                        <div class="row">
                            <div class="col-xs-4 col-md-5 col-lg-7 "><b>
                                    @if($reportPage=='1')
                                        {{ trans('sensors.sensorlog24') }}
                                        {{ date('( d M Y )')}}
                                        <!-- {{ trans('sensors.kompot') }}-->
                                    @else 
                                        {{ trans('sensors.sensorlog1threadingOf30days') }}
                                        {{ date('( d M Y )')}}
                                         <!-- //{{ trans('sensors.phnom_penh') }} -->
                                    @endif
                                    <br/>
                                     @foreach($sensors as $sensor)
                                       <p> {{ $sensor->additional_location_info }}</p>
                               
                                     @endforeach

                                </b></div>
                            <div class="col-xs-8 col-md-7 col-lg-5 ">
                                <a href="sensorlogReportInChart?sensor_id={{$sensorId}}&type={{$reportPage}}">
                                    <button class="btn btn-info" id="sensor_log_report_graph">
                                        <i class="fa fa-line-chart  fa-lg" aria-hidden="true"></i>
                                        {{ trans('sensors.sensor_log_graph_link') }}
                                    </button>
                                </a>
                                @if($reportPage=='2')
                                    <a href="sensorsLog20?sensor_id={{$sensorId}}">
                                @else
                                    <a href="sensorsLog1thReadingOf30days?sensor_id={{$sensorId}}">
                                @endif
                                        <button class="btn btn-info" id="sensor_log_report">
                                            <i class="fa fa-table  fa-lg" aria-hidden="true"></i>
                                            @if($reportPage=='1')
                                                {{ trans('sensors.sensorlog1threadingOf30daysBtn') }}
                                                
                                            @else
                                                {{ trans('sensors.sensorlog24Btn') }}
                                               
                                            @endif
                                        </button>
                                    </a>

                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                    <div style="height:870px; overflow-y: scroll; overflow-x: hidden;">
                        <table class="table table-bordered" id="sensorlogs-table">
                            <thead>
                            <tr>
                                <th>Sensor ID</th>
                                <th>Stream Height</th>
                                <th>Charging</th>
                                <th>Voltage</th>
                                <th>Timestamp</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ csrf_field() }}
                            @foreach($sensorlogs as $sensorlog)
                                <tr>
                                    <td>{{$sensorlog->sensor_id}}</td>
                                    <td>
                                        {{$sensorlog->stream_height}}
                                    </td>
                                    <td>
                                        {{$sensorlog->charging}}
                                    </td>
                                    <td>
                                       {{$sensorlog->voltage}}
                                    </td>
                                    <td>
                                        {{$sensorlog->timestamp}}
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        </div> <!-- dive scroll -->
                    </div><!-- /panel-body -->
                </div><!-- /panel panel-default -->
        </div><!--/.cold-->
    </div><!--/.row-->
</div>	<!--/.main-->

<script>

    $(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('#sensorlogs-table').DataTable({
            "paging":   true
        });
    });

</script>
@endsection




