@extends('layouts.master')
@section('datatable-css')
    <link href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet"
          xmlns="http://www.w3.org/1999/html"/>
    <link href="//cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" rel="stylesheet" />
@endsection
@section('datatable-js')
    <script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
@endsection
@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active"> {{ trans('sensors.sensor_trigger_mgmt') }} </li>
            </ol>
        </div><!--/.row-->
        <div class="row" >
            <div class="col-xs-12 col-md-12 col-lg-12" >
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <div class="row">
                            <div class="col-xs-7 col-md-8 col-lg-9">
                                <b>{{ trans('sensors.sensor_trigger_mgmt') }}</b>
                            </div>
                            <div class="col-xs-5 col-md-4 col-lg-3 text-right">
                                <button class="btn btn-info" id="add_sensor_trigger">
                                    <i class="fa fa-plus-circle fa-lg" aria-hidden="true"></i>
                                    {{ trans('sensors.add_new') }}
                                </button>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.panel-heading -->
                    <div class="panel-body">
                        <table class="table table-striped responsive" id="trigger-tbl" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <th class="text-center">{{ trans('pages.tbl_title_number') }}</th>
                                <th class="text-center">{{ trans('sensors.sensor_id') }}</th>
                                <th class="text-center">{{ trans('sensors.warning_level') }}</th>
                                <th class="text-center">{{ trans('sensors.emergency_level')}}</th>
                                <th class="text-center">{{ trans('sensors.affected_communes')}}</th>
                                <th class="text-center">{{ trans('sensors.phone_numbers')}}</th>
                                <th class="text-center">{{ trans('sensors.sound_file_warning')}}</th>
                                <th class="text-center">{{ trans('sensors.sound_file_emergency')}}</th>
                                <th class="text-center">{{ trans('sensors.emails')}}</th>
                                <th class="text-center">{{ trans('sensors.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{ csrf_field() }}
                            @if(!empty($sensor_trigger))
                                <?php $i=0; ?>
                            @foreach($sensor_trigger as $sensor_trigger)
                                <?php
                                    $i=$i+1;
                                    // display each affected communes in readable text
                                    $affected_commune_list=explode(',', $sensor_trigger->affected_communes);
                                    $affected_commune = "";
                                    foreach($affected_commune_list as $affected_commune_list)
                                    {
                                        $affected_communes = \DB::table('commune')->where('CCode', $affected_commune_list)->get();
                                        if (\App::getLocale()=='km')
                                            $affected_commune .= $affected_communes[0]->CName_kh . ", ";
                                        else
                                            $affected_commune .= $affected_communes[0]->CName_en . ", ";
                                    }
                                ?>
                                <tr>
                                    <td class="text-center">{{ $i }}</td>
                                    <td class="text-center">
                                        {{ $sensor_trigger->sensor_id }}
                                    </td>
                                    <td class="text-center">
                                        {{ $sensor_trigger->level_warning }}
                                    </td>
                                    <td class="text-center">
                                       {{ $sensor_trigger->level_emergency }}
                                    </td>
                                    <td>
                                        {{ $affected_commune }}
                                    </td>
                                    <td>
                                        {{ $sensor_trigger->phone_numbers }}
                                    </td>
                                    <td>
                                        {{ $sensor_trigger->warning_sound_file }}
                                    </td>
                                    <td>
                                        {{ $sensor_trigger->emergency_sound_file }}
                                    </td>
                                    <td>
                                        {{ $sensor_trigger->emails_list }}
                                    </td>
                                    <td>
                                       <!-- Edit Sensor Record Button -->
                                       <button class="btn btn-info" id="edit_sensor_info" name="{{ $sensor_trigger->sensor_id }}">
                                            <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                            {{ trans('auth.edit') }} </button>

                                       <!-- Delete Sensor Record Button -->
                                       <button class="btn btn-danger" id="delete_sensor_info" name="{{ $sensor_trigger->sensor_id }}">
                                            <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                            {{ trans('auth.delete') }}</button>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                    </div>
                </div>
            </div>
        </div><!--/.row-->
    </div>	<!--/.main-->

    <!-- Add Sensor trigger Info Modal -->
    <div class="modal fade" id="modal_add_sensor_trigger_record" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title text-center" id="myModalLabel">{{ trans('sensors.modal_title_add_sensor') }}</h4>
                </div>
                <div class='modal-body'>
                    {{ trans('sensors.sensor_id') }} <input type='text' id='sensor_id' name='sensor_id' /><br />
                    {{ trans('sensors.warning_level') }} <input type='text' id='warning_level' name='warning_level' /><br />
                    {{ trans('sensors.emergency_level')}} <input type='text' id='emergency_level' name='emergency_level'  /><br />
                    {{ trans('sensors.affected_communes')}} <br />
                        <select>
                            <option> {{ trans('pages.select_province') }}</option>
                        </select>
                        <select>
                            <option> {{ trans('pages.select_district') }}</option>
                        </select>
                        <select multiple>
                            <option> {{ trans('pages.select_communes') }}</option>
                        </select>
                    <br />
                    {{ trans('sensors.phone_numbers')}}
                        <textarea rows="4" cols="50" id='phone_numbers' name='phone_numbers'/></textarea <br />
                    {{ trans('sensors.sound_file_warning')}} <input type='file' id='warning_sound_file' name='warning_sound_file'/><br />
                    {{ trans('sensors.sound_file_emergency')}} <input type='file' id='emergency_sound_file' name='emergency_sound_file'/><br />
                    {{ trans('sensors.emails')}}
                        <textarea rows="4" cols="50" id='email_list' name='email_list'/></textarea <br />

                    </div>
                <div class='modal-footer'>
                    <button class='btn btn-default' data-dismiss='modal'>
                        <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                        {{trans('sensors.cancel')}}
                    </button>
                    <button class='btn btn-primary' data-dismiss='modal' id='add_sensor_trigger_data'>
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
//            var table = $('#trigger-tbl').DataTable({
//                "paging":   true,
//                "responsive": true,
//            });
            $('#trigger-tbl').DataTable();

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
            $(document).on('click', '#add_sensor_trigger', function()
            {
                $('#modal_add_sensor_trigger_record').modal('show');
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



