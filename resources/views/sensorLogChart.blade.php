@extends('layouts.master')
@section('content')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active"> {{ trans('sensors.sensor_mgmt') }} </li>
            </ol>
        </div><!--/.row-->
        <div class="row" >
            <div class="col-xs-12 col-md-12 col-lg-12" >
                <div class="panel panel-default"  style="padding-bottom: 30px;">
                    <div class="panel-heading text-center ">
                        <div class="row">
                            <div class="col-xs-8 col-md-9 col-lg-10 "><b>{{ trans('sensors.modal_title_edit_sensor_table') }}</b></div>
                            <div class="col-xs-6 col-md-3 col-lg-2 "><button class="btn btn-info" id="add_sensor">
                                    <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                    {{ trans('sensors.add_new') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div id="div_chart"></div>
                            <?= Lava::render('LineChart','SensorLogChart','div_chart') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--/.row-->

@endsection
