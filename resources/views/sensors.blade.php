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
                        <table class="table table-bordered" id="province-table">
                            <thead>
                            <tr>
                                <th>Sensor ID</th>
                                <th>Location Code</th>
                                <th>Location Information</th>
                                <th>Location Coordinates</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ csrf_field() }}
                            @foreach($sensors as $sensor)
                                <tr>
                                    <td>{{$sensor->sensor_id}}</td>
                                    <td>
                                        {{$sensor->location_code}}
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
                    ID: <input type='text' id='txtSensorID' name='sensorID' /><br />
                    Location: <input type='text' id='txtLocationCode' name='locationCode' /><br />
                    Additional Info: <input type='text' id='txtAdditionalLocationInfo' name='additionalLocationInfo'  /><br />
                    Latitude: <input type='text' id='txtLocationLatitude' name='locationLatitude'/><br />
                    Longitude: <input type='text' id='txtLocationLongitude' name='locationLongitude'/><br />
                    </div>
                <div class='modal-footer'>
                    <button class='btn btn-default' data-dismiss='modal'>
                        <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                        {{trans('sensors.cancel')}}
                    </button>
                    <button class='btn btn-primary' data-dismiss='modal' id='add_sensor_data'>
                        <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i>
                        {{ trans('sensors.add')  }}
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
                <span id='sensor_info_detail'></span>

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
                "paging":   true
            });

            // global csrf token variable
            var token = "{{ csrf_token() }}";

            //$('#edit_sensor_info1').click(function(e){
            $(document).on('click', '#edit_sensor_info', function()
            {
                var btn_val = $(this).attr('name');
                //alert(btn_val);
                console.log(token);
                $.ajax({
                    type: "POST",
                    url: "{{ url('/sensor_info') }}",
                    data: {_token: token, id: btn_val},
                    cache: false,
                    success: function(result)
                    {
                        // alert("success= " + result[0].id);
                        $("#sensor_info_detail").html(result).show();
                        $('#modal_sensor_record').modal('show');
                    }
                });
                return false;
            });

            /* Display Modal Add Sensor Data */
            $(document).on('click', '#add_sensor', function()
            {
                //alert(token);
                $('#modal_add_sensor_record').modal('show');
                return false;
            });

            /* Add Sensor Data */
            $(document).on('click', '#add_sensor_data', function()
            {

                var txtSensorID = $('#txtSensorID').val();
                var txtLocationCode = $('#txtLocationCode').val();
                var txtAdditionalLocationInfo = $('#txtAdditionalLocationInfo').val();
                var txtLocationLatitude = $('#txtLocationLatitude').val();
                var txtLocationLongitude = $('#txtLocationLongitude').val();

                $.ajax({
                    type: "POST",
                    url: "{{ url('/add_new_sensor_info') }}",
                    data: {_token: token, sensor_code: txtSensorID, loc_code: txtLocationCode, sensor_additional_info: txtAdditionalLocationInfo, sensor_lat: txtLocationLatitude, sensor_long: txtLocationLongitude},
                    cache: false,
                    success: function(result)
                    {
                        location.reload();
                        alert('New Sensor Already Added!');
                    },
                    error: function(e)
                    {
                        console.log(e);
                    }
                });
                return false;
            });

            /* Save Edited data of a user */
            $(document).on('click', '#save_change_sensor', function()
            {
                var txtLocationCode = $('#txtLocationCodeEdit').val();
                var txtAdditionalLocationInfo = $('#txtAdditionalLocationInfoEdit').val();
                var txtLocationCoordinates = $('#txtLocationCoordinatesEdit').val();
                var btn_val = $(this).attr('name');
                alert(btn_val);
                //$('#modal_user_profile').modal('hide');
                //alert('save= ' + btn_user_val + " name= " + txt_user_name + " email= " + txt_user_email);
                $.ajax({
                    type: "POST",
                    url: "{{ url('/save_change_sensor_info') }}",
                    data: {_token: token, id: btn_val, loc_code: txtLocationCode, additon_loc_info: txtAdditionalLocationInfo, sensor_coordinates: txtLocationCoordinates},
                    cache: false,
                    success: function(result)
                    {
                        location.reload();
                    }
                });
                return false;

            });

            /* Delete User */
            $(document).on('click', '#delete_sensor_info', function()
            {
                // alert("delete");
                var btn_delete_val = $(this).attr('name');
                $('#modal_delete_sensor_info').modal('show');
                $('#btn_delete_yes').click(function(e){
                    //alert('delete=yes=> ' + btn_delete_val);
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




