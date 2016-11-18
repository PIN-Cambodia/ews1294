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
                <li class="active"> {{ trans('sensors.modal_title_edit_sensor') }} </li>
            </ol>
        </div><!--/.row-->
        <div class="row" >
            <div class="col-xs-12 col-md-12 col-lg-12" >
                <div class="panel panel-default">
                    <div class="panel-heading text-center "><b>{{ trans('sensors.sensorlog24') }}</b>

                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered" id="sensorlogs-table">
                            <thead>
                            <tr>
                                <th>Sensor ID</th>
                                <th>Stream Height</th>
                                <th>Charging</th>
                                <th>Voltage</th>
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
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
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




