@extends('layouts.master')
@section('content')
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
            <li class="active"> {{ trans('sensors.sensor_trigger_mgmt') }} </li>
        </ol>
    </div><!--/.row-->
    <div class="row" >
        <div class="col-xs-12 col-md-12 col-lg-12">
            <div class="fixed-panel">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <div class="row">
                            <div class="col-xs-7 col-md-8 col-lg-9">
                                <b>{{ trans('sensors.sensor_trigger_mgmt') }}</b>
                                <br>
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
                                <th class="text-center">{{ trans('sensors.date')}}</th>
                                <th class="text-center">{{ trans('sensors.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($sensor_trigger_data))
                                <?php $i=0; ?>
                                @foreach($sensor_trigger_data as $sensor_trigger)
                                    <?php
                                    $i=$i+1;
                                    // display each affected communes in readable text
                                    $affected_commune_list=explode(',', $sensor_trigger->affected_communes);
                                    $affected_communes = "";
                                    foreach($affected_commune_list as $affected_commune_list)
                                    {
                                        $affected_commune = \DB::table('commune')->where('CCode', $affected_commune_list)->get();
                                        if(!empty($affected_commune))
                                        {
                                            if (\App::getLocale()=='km')
                                                $affected_communes .= $affected_commune[0]->CName_kh . ", ";
                                            else
                                                $affected_communes .= $affected_commune[0]->CName_en . ", ";
                                        }
                                    }
                                    ?>
                                    <tr>
                                        <td class="text-center"> {{ $i }} </td>
                                        <td class="text-center"> {{ $sensor_trigger->sensor_id }} </td>
                                        <td class="text-center"> {{ $sensor_trigger->level_warning }} </td>
                                        <td class="text-center"> {{ $sensor_trigger->level_emergency }} </td>
                                        <td> {{ $affected_communes }} </td>
                                        <td> {{ $sensor_trigger->phone_numbers }} </td>
                                        <td> {{ $sensor_trigger->warning_sound_file }} </td>
                                        <td> {{ $sensor_trigger->emergency_sound_file }} </td>
                                        <td> {{ $sensor_trigger->emails_list }} </td>
                                        <td> {{ $sensor_trigger->created_at }} </td>
                                        <td>
                                            <!-- Edit Sensor Trigger Record Button -->
                                            <button class="btn btn-info" id="edit_sensor_trigger_info" name="{{ $sensor_trigger->id }}">
                                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                                {{ trans('auth.edit') }}
                                            </button>
                                            <!-- Delete Sensor Trigger Record Button -->
                                            <button class="btn btn-danger" id="delete_sensor_trigger_info" name="{{ $sensor_trigger->id }}">
                                                <i class="fa fa-trash-o fa-lg" aria-hidden="true"></i>
                                                {{ trans('auth.delete') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div><!-- /panel-body -->
                </div><!-- /panel panel-default -->
            </div><!-- /fixed-panel -->
        </div><!-- /col -->
    </div><!-- /row -->
</div>	<!--/.main-->

<!-- Add New Sensor trigger Info Modal -->
<div class="modal fade" id="modal_add_sensor_trigger_record" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="myModalLabel">{{ trans('sensors.modal_title_add_sensor_trigger') }}</h4>
            </div>
            {!! Form::open(array('url' =>'/addsensortrigger', 'method'=>'post','id'=>'add_sstr_form', 'files' => true)) !!}
            <div class='modal-body'>
                {{ trans('sensors.sensor_id') }}
                <select class="fullwidth select_style" id="sensor_id" name="sensor_id" required>
                    @if(!empty($sensor_list_add_new))
                        <option value=''> {{ trans('sensors.select_sensor') }}</option>
                        @foreach($sensor_list_add_new as $each_sensor)
                            <option value="{{ $each_sensor->sensor_id }}">{{ $each_sensor->sensor_id }}</option>
                        @endforeach
                    @endif
                </select><br />
                {{ trans('sensors.warning_level') }}
                <input type='text' class='numeric' id='warning_level' name='warning_level' /><br />
                {{ trans('sensors.emergency_level')}}
                <input type='text' class='numeric' id='emergency_level' name='emergency_level'  /><br />
                {{ trans('sensors.affected_communes')}} <br />
                <div class="row">
                    <div class="col-lg-3">
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
                        <select class="fullwidth select_style ss_district" id="ss_district"></select>
                    </div>
                    <div class="col-lg-6 ss_commune_div" id="ss_commune_div">
                        <div class="row select_style_height">
                            <div class="col-lg-12 ss_commune" id="ss_commune" name="ss_communes"></div>
                        </div>
                    </div>
                </div>
                <br />
                {{ trans('sensors.phone_numbers')}} <br />
                <textarea class='multinumbers' rows='4' cols='50' id='phone_numbers' name='phone_numbers' placeholder='{{ trans('sensors.enter_multiple_phone_numbers') }}'></textarea> <br />
                {{ trans('sensors.sound_file_warning')}}
                <input type='file' id='warning_sound_file' name='warning_sound_file' accept='audio/*'/><br />
                {{ trans('sensors.sound_file_emergency')}}
                <input type='file' id='emergency_sound_file' name='emergency_sound_file' accept='audio/*'/><br />
                {{ trans('sensors.emails')}} <br />
                <textarea rows='4' cols='50' id='email_list' name='email_list' placeholder='{{ trans('sensors.enter_multiple_emails') }}'></textarea> <br />
                <span id="error_email_format"></span>
            </div><!-- /.modal-body -->
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss='modal'>
                    <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                    {{trans('sensors.cancel')}}
                </button>
                <button class='btn btn-primary' id='add_sensor_trigger_data'>
                    <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i>
                    {{ trans('sensors.add_new')  }}
                </button>
            </div><!-- /.modal-footer -->
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Edit New Sensor trigger Info Modal -->
<div class="modal fade" id="modal_edit_sensor_trigger_record" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            {!! Form::open(array('url' =>'/saveeditsensortrigger', 'method'=>'post','id'=>'edit_sstr_form', 'files' => true)) !!}
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Cancel"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title text-center" id="myModalEdit">{{ trans('sensors.modal_title_edit_sensor_trigger') }}</h4>
            </div><!-- /.modal-header -->
            <div class='modal-body' id="modal_edit_content">

            </div><!-- /.modal-body -->
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss='modal'>
                    <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                    {{trans('sensors.cancel')}}
                </button>
                <button class='btn btn-primary' id='edit_sensor_trigger_data'>
                    <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i>
                    {{ trans('sensors.save')  }}
                </button>
            </div><!-- /.modal-footer -->
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- show confirm delete dialog -->
<div class="modal fade" id="modal_delete_sensor_trigger_record" data-backdrop="static" tabindex="-1" role="dialog">
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

<!--show waiting loading dialog -->
<div class="modal fade fixed-dialog-center" id="modal_waiting" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog">
        <div><h3>{{ trans('sensors.waiting_dialog') }}</h3></div><br/>
        <div class="spinner"></div>
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    $(function() {
        // global csrf token variable
        var token = $('input[name=_token]').val();

        $('#trigger-tbl').DataTable({
            scrollY: '50vh',
            deferRender:    true,
            scroller:       true
        });

        $("#ss_district").hide();
        $("#ss_commune_div").hide();

        /* clear all data when modal onClose or dismiss */
        $('#modal_add_sensor_trigger_record').on('hidden.bs.modal', function () {
            $("#ss_district").hide();
            $("#ss_commune_div").hide();
            //$(this).find("input,textarea,select, #ss_commune_div:input").val('').end();
            $(this)
                .find("input, textarea, select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
        });
        /* Show Modal Add New Sensor Trigger */
        $(document).on('click', '#add_sensor_trigger', function ()
        {
            $('#modal_add_sensor_trigger_record').modal('show');
            return false;
        });

        // add sensor trigger data is submitted
        $(document).on('click', '#add_sensor_trigger_data', function ()
        {
            var check_invalid_email = checkValidateEmail($('#email_list'));
            if (check_invalid_email != "")
                $('#error_email_format').html("<font color='red'>{{trans('sensors.error_email_validation')}}" + check_invalid_email + "</font>");
            var sensor_id = $('#sensor_id').val();
            if(sensor_id!="")
            {
                if(check_invalid_email=="")
                {
                    $('#modal_add_sensor_trigger_record').modal('hide');
                    $('#modal_waiting').modal('show');
                    $( "#add_sstr_form" ).submit();
                }
                return false;
            }

        });

        // edit sensor trigger data is submitted
         $(document).on('click', '#edit_sensor_trigger_data', function ()
         {
             var check_invalid_email = checkValidateEmail($('#email_list_edit'));
             //console.log("1. calling func= " + check_invalid_email);
             if (check_invalid_email != "")
                $('#error_email_format-edit').html("<font color='red'>{{trans('sensors.error_email_validation')}}" + check_invalid_email + "</font>");
            else
             {
                $('#modal_edit_sensor_trigger_record').modal('hide');
                $('#modal_waiting').modal('show');
                $('#edit_sstr_form').submit();
             }
             return false;
         });

        /* Show Modal Edit Sensor Trigger */
        $(document).on('click', '#edit_sensor_trigger_info', function ()
        {
            var edit_val = $(this).attr('name');
            $.ajax({
                type: "POST",
                url: "{{ url('/geteditsensortrigger') }}",
                data: {_token: token, edit_val: edit_val},
                cache: false,
                success: function(result)
                {
                    $('#modal_edit_content').html(result);
                    $('#modal_edit_sensor_trigger_record').modal('show');
                },
                // error: function() {
                //   alert('sorry, data cannot be fetch');
                // }
            });
            //$('#modal_edit_sensor_trigger_record').modal('show');
            return false;
        });
        /* Delete Sensor Trigger data */
        $(document).on('click', '#delete_sensor_trigger_info', function()
        {
            var del_val = $(this).attr('name');
            $('#modal_delete_sensor_trigger_record').modal('show');
            $('#btn_delete_yes').click(function(e){
                $('#modal_delete_sensor_trigger_record').modal('hide');
                $('#modal_waiting').modal('show');
                $.ajax({
                    type: "POST",
                    url: "{{ url('/deletesensortrigger') }}",
                    data: {_token: token, delete_val: del_val},
                    cache: false,
                    success: function()
                    {
                        $('#modal_waiting').modal('hide');
                        location.reload();

                    },
                    // error: function() {
                    //   alert('sorry, data cannot be fetch');
                    // }
                });
            });
            return false;
        });
    }); // ./$(function()

</script>
@endsection
