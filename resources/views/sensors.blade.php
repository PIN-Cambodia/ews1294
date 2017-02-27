@extends('layouts.master')
@section('content')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row" style="background: white;">
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
                        <table class='table responsive cell-border table-bordered' id='province-table' cellspacing='0' width='100%'>
                            <thead>
                            <tr>
                                <th>{{ trans('sensors.sensor_id') }}</th>
                                <th>{{ trans('sensors.location') }}</th>
                                <th>{{ trans('sensors.additional_Info') }}</th>
                                <th>{{ trans('sensors.location_coordinates') }}</th>
                                <th>{{ trans('sensors.action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ csrf_field() }}
                            @foreach($sensors as $sensor)
                                <?php
                                    $location= "";
                                    $location_name = \DB::table('commune')->where('CCode', $sensor->location_code)->first();
                                    if(!empty($location_name)){
                                        if (\App::getLocale()=='km')
                                            $location = $location_name->CName_kh;
                                        else $location = $location_name->CName_en;
                                    }
                                ?>
                                <tr>
                                    <td>{{$sensor->sensor_id}}</td>
                                    <td>
                                        {{$location}}
                                    </td>
                                    <td>
                                        {{$sensor->additional_location_info}}
                                    </td>
                                    <td>
                                        {{$sensor->location_coordinates}}
                                    </td>
                                    <td>
                                        <!-- Edit Sensor Record Button -->
                                        <button class="btn btn-primary" id="edit_sensor_info" name="{{ $sensor->id }}">
                                            <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                            {{ trans('auth.edit') }} </button>

                                        <!-- Delete Sensor Record Button -->
                                        <button class="btn btn-danger" id="delete_sensor_info" name="{{ $sensor->id }}">
                                            <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                            {{ trans('auth.delete') }}</button>
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

    <!-- Add Sensor Info Modal -->
    <div class="modal fade" id="modal_add_sensor_record" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center" id="myModalLabel">{{ trans('sensors.modal_title_add_sensor') }}</h4>
                </div>
                <div class='modal-body'>
                    {{ trans('sensors.sensor_id') }} <br>
                    <input type='text' id='txtSensorID' name='sensorID' /><br />
                    {{ trans('sensors.location') }} <br>
                    <div class="row">
                        <div class="col-lg-3">
                            {{ csrf_field() }}
                            <select class="fullwidth select_style ss_province" id="ss_province">
                                @if(!empty($all_province))
                                    <option value='0'> {{ trans('pages.select_province') }}</option>
                                    @foreach($all_province as $each_province)
                                        @if (App::getLocale()=='km')
                                            <option value="{{ $each_province->PROCODE }}">{{ $each_province->PROVINCE_KH }}</option>
                                        @else
                                            <option value="{{ $each_province->PROCODE }}">{{ $each_province->PROVINCE }}</option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="col-lg-3">
                            <select class="fullwidth select_style ss_district ss_district_select" id="ss_district_select"></select>
                        </div>
                        <div class="col-lg-6 ss_commune_select_div" id="ss_commune_select_div">
                            <select class="fullwidth select_style ss_commune_select" id="ss_commune_select" name="ss_communes_select"></select>
                        </div>
                    </div><br/>
                    {{ trans('sensors.additional_Info') }} <br>
                    <input type='text' id='txtAdditionalLocationInfo' name='additionalLocationInfo'  /><br />
                    {{ trans('sensors.latitude') }} <br>
                    <input type='text' id='txtLocationLatitude' name='locationLatitude'/><br />
                    {{ trans('sensors.longitude') }} <br>
                    <input type='text' id='txtLocationLongitude' name='locationLongitude'/><br />
                </div>
                <div class='modal-footer'>
                    <button class='btn btn-default' data-dismiss='modal'>
                        <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                        {{trans('sensors.cancel')}}
                    </button>
                    <button class='btn btn-primary' data-dismiss='modal' id='add_sensor_data'>
                        <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i>
                        {{ trans('sensors.add_new')  }}
                    </button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Edit Sensor Info Modal -->
    <div class="modal fade" id="modal_sensor_record" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">{{ trans('sensors.modal_title_edit_sensor') }}</h4>
                </div>
                <span id='sensor_info_detail'>
                    {{ csrf_field() }}
                </span>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- show confirm delete dialog -->
    <div class="modal fade" id="modal_delete_sensor_info" data-backdrop="static" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><b>{{ trans('sensors.dialog_confirm') }}</b></h3>
                </div>
                <div class="modal-body">
                    <h4>
                        {{ trans('sensors.action_confirmation_question') }} <br /><br />
                        {{ trans('sensors.action_confirmation_yes') }} <br />
                        {{ trans('sensors.action_confirmation_no') }} <br /><br />
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
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var table = $('#province-table').DataTable({
                "paging":   true,
                scrollY: '50vh'
            });

            // global csrf token variable
            var token = "{{ csrf_token() }}";

            $(document).on('click', '#edit_sensor_info', function()
            {
                var btn_val = $(this).attr('name');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/sensor_info') }}",
                    data: {_token: token, id: btn_val},
                    cache: false,
                    success: function(result)
                    {
                        $("#sensor_info_detail").html(result).show();
                        $('#modal_sensor_record').modal('show');
                    }
                });
                return false;
            });

            /* Display Modal Add Sensor Data */
            $(document).on('click', '#add_sensor', function()
            {
                $('#modal_add_sensor_record').modal('show');
                return false;
            });

            /* Add New Sensor */
            $(document).on('click', '#add_sensor_data', function()
            {
                var txtSensorID = $('#txtSensorID').val();
                var commune_code = $('#ss_commune_select').val();
                var txtAdditionalLocationInfo = $('#txtAdditionalLocationInfo').val();
                var txtLocationLatitude = $('#txtLocationLatitude').val();
                var txtLocationLongitude = $('#txtLocationLongitude').val();
                // post data to server using ajax
                $.ajax({
                    type: "POST",
                    url: "{{ url('/add_new_sensor_info') }}",
                    data: {_token: token, sensor_code: txtSensorID, commune_code: commune_code, sensor_additional_info: txtAdditionalLocationInfo, sensor_lat: txtLocationLatitude, sensor_long: txtLocationLongitude},
                    cache: false,
                    success: function(result)
                    {
                        location.reload();
                    },
                    error: function(e)
                    {
                        console.log(e);
                    }
                });
                return false;
            });

            /* Save Edited data of a sensor */
            $(document).on('click', '#save_change_sensor', function()
            {
                var ccode = $('#ss_commune_option').val();
                var txtAdditionalLocationInfo = $('#txtAdditionalLocationInfoEdit').val();
                var txtLocationCoordinates = $('#txtLocationCoordinatesEdit').val();
                var btn_val = $(this).attr('name');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/save_change_sensor_info') }}",
                    data: {_token: token, id: btn_val, ccode: ccode, additon_loc_info: txtAdditionalLocationInfo, sensor_coordinates: txtLocationCoordinates},
                    cache: false,
                    success: function(result)
                    {
                        location.reload();
                    }
                });
                return false;
 
            });

            /* Delete Sensor Info */
            $(document).on('click', '#delete_sensor_info', function()
            {
                var btn_delete_val = $(this).attr('name');
                $('#modal_delete_sensor_info').modal('show');
                $('#btn_delete_yes').click(function(e){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('/delete_sensor_info') }}",
                        data: {_token: token, delete_val: btn_delete_val},
                        cache: false,
                        success: function()
                        {
                            location.reload();
                        }
                    });
                    return false;
                });
                return false;
            });

        });

    </script>
@endsection
