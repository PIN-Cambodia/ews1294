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
                                            <button class="btn btn-info" id="edit_sensor_trigger_info" name="{{ $sensor_trigger->sensor_id }}">
                                                <i class="fa fa-pencil fa-lg" aria-hidden="true"></i>
                                                {{ trans('auth.edit') }}
                                            </button>
                                            <!-- Delete Sensor Trigger Record Button -->
                                            <button class="btn btn-danger" id="delete_sensor_trigger_info" name="{{ $sensor_trigger->sensor_id }}">
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
            {{ csrf_field() }}
            <div class='modal-body'>
                {{ trans('sensors.sensor_id') }}
                <select class="fullwidth select_style" id="sensor_id" name="sensor_id">
                    @if(!empty($sensor_list))
                        <option value='0'> {{ trans('sensors.select_sensor') }}</option>
                        @foreach($sensor_list as $each_sensor)
                            <option value="{{ $each_sensor->sensor_id }}">{{ $each_sensor->sensor_id }}</option>
                        @endforeach
                    @endif
                </select><br />
                {{ trans('sensors.warning_level') }}
                <input type='text' id='warning_level' name='warning_level' /><br />
                {{ trans('sensors.emergency_level')}}
                <input type='text' id='emergency_level' name='emergency_level'  /><br />
                {{ trans('sensors.affected_communes')}} <br />
                <div class="row">
                    <div class="col-lg-3">
                        <select class="fullwidth select_style" id="ss_province">
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
                        <select class="fullwidth select_style" id="ss_district"></select>
                    </div>
                    <div class="col-lg-6" id="ss_commune_div">
                        <div class="row select_style_height">
                            <div class="col-lg-12" id="ss_commune" name="ss_communes"></div>
                        </div>
                    </div>
                </div>
                <br />
                {{ trans('sensors.phone_numbers')}} <br />
                <textarea rows="4" cols="50" id='phone_numbers' name='phone_numbers'></textarea> <br />
                {{ trans('sensors.sound_file_warning')}}
                <input type='file' id='warning_sound_file' name='warning_sound_file' accept='audio/*'/><br />
                {{ trans('sensors.sound_file_emergency')}}
                <input type='file' id='emergency_sound_file' name='emergency_sound_file' accept='audio/*'/><br />
                {{ trans('sensors.emails')}} <br />
                <textarea rows="4" cols="50" id='email_list' name='email_list'></textarea> <br />
            </div><!-- /.modal-body -->
            <div class='modal-footer'>
                <button class='btn btn-default' data-dismiss='modal'>
                    <i class='fa fa-times fa-lg' aria-hidden='true'></i>
                    {{trans('sensors.cancel')}}
                </button>
                <button type="submit" class='btn btn-primary' id='add_sensor_trigger_data'>
                    <i class='fa fa-floppy-o fa-lg' aria-hidden='true'></i>
                    {{ trans('sensors.add_new')  }}
                </button>
            </div><!-- /.modal-footer -->
            {!! Form::close() !!}
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
    $(function() {
        $('#trigger-tbl').DataTable({
            scrollY: '50vh',
            deferRender:    true,
            scroller:       true
        });

        $("#ss_district").hide();
        $("#ss_commune_div").hide();
        if($("#ss_district") == "")
        {
            alert("empty");
        }

        // global csrf token variable
        var token = $('input[name=_token]').val();

        /* Show Districts select option */
        $('#ss_province').change(function () {
            var province_id = $('#ss_province').val();
            if(province_id !="")
            {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/getDistricts') }}",
                    data: {_token: token, province_id: province_id},
                    cache: false,
                    success: function(result)
                    {
                        $("#ss_district").html(result).show();
                    }
                });
            }
            return false;
        });

        /* Show Communes select option */
        $('#ss_district').change(function () {
            var distric_id = $('#ss_district').val();
            if(distric_id != "")
            {
                $.ajax({
                    type: "POST",
                    url: "{{ url('/getCommunes') }}",
                    data: {_token: token, distric_id: distric_id},
                    cache: false,
                    success: function(result)
                    {
                        $("#ss_commune").html(result);
                        $("#ss_commune_div").show();
                    }
                });
            }
            return false;
        });

        /* clear all data when modal onClose or dismiss */
        $('#modal_add_sensor_trigger_record').on('hidden.bs.modal', function () {
            $("#ss_district").hide();
            $("#ss_commune_div").hide();
            //$(this).find("input,textarea,select, #ss_commune_div:input").val('').end();
            $(this)
                    .find("input,textarea,select")
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


    }); // ./$(function()
</script>
@endsection




