@extends('layouts.master')
@section('content')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active"> {{ trans('sensors.sensor_mgmt') }}
                 </li>
            </ol>
        </div><!--/.row-->
        <div class="row" >
            <div class="col-xs-12 col-md-12 col-lg-12" >
                <div class="panel panel-default"  style="padding-bottom: 30px;">
                    <div class="panel-heading text-center ">
                        <div class="row">
                            <div class="col-xs-8 col-md-9 col-lg-10 ">
                                <b>
                                    @if($graph_type=='1')
                                        {{ trans('sensors.sensorlog6_graph')}}
                                        {{ date('( d-M-Y )')}}
                                        {{ trans('sensors.kompot') }}    

                                    @else
                                        {{ trans('sensors.sensorlog1threadingOf30days_graph') }}
                                         {{ trans('sensors.phnom_penh') }}
                                          
                                    @endif
                                        }
                                        }

                                </b>
                            </div>
                            <div class="col-xs-6 col-md-3 col-lg-2 ">
                                @if($graph_type=='1')
                                    <a href="sensorsLog20?sensor_id={{$sensorId}}">
                                @else
                                    <a href="sensorsLog1thReadingOf30days?sensor_id={{$sensorId}}">
                                @endif
                                        <button class="btn btn-info" id="add_sensor">
                                            <i class="fa fa-table  fa-lg" aria-hidden="true"></i>
                                            {{ trans('sensors.sensor_log_table_link') }}
                                        </button>
                                    </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="div_chart" style="padding: 3px;"></div>
                            <?= Lava::render('LineChart','SensorLogChart','div_chart') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.row-->

@endsection
