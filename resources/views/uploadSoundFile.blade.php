@extends('layouts.master')
@section('content')
<section id="uploadsoundfile">
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active"> {{ trans('menus.upload_sound_file') }} </li>
            </ol>
        </div><!--/.row-->
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12 ">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="fixed-panel">
                            <!-- Opening a form -->
                            @if(Session::has('message'))
                                <p class="alert-danger">{{Session::get('message')}}</p>
                            @endif
                            {!! Form::open(array('route' =>'call.them', 'method'=>'post','id'=>'uploadForm', 'files' => true)) !!}
                                <div class="row">
                                    <div class="col-xs-6 col-md-6 col-lg-6">
                                        <div class="row topspace">
                                            <div class="col-xs-4 col-md-4 col-lg-4">
                                                {{ trans('pages.province_:') }}
                                            </div>
                                            <div class="col-xs-8 col-md-8 col-lg-8">
                                                <select name="province_id" id="province">
                                                    <option value="AllProvinces"> {{ trans('pages.all_provinces') }} </option>
                                                    @foreach ($provinces as $item)
                                                        @if (App::getLocale()=='km')
                                                            <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE_KH }}</option>
                                                        @else
                                                            <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div><!-- /.row -->
                                        <div class="row topspace">
                                            <div class="col-xs-4 col-md-4 col-lg-4">
                                                {{ trans('pages.sound_file_:') }}
                                            </div>
                                            <div class="col-xs-8 col-md-8 col-lg-8">
                                                <input type="file" name="soundFile" id="soundFile" accept="audio/*"><br />
                                            </div>
                                        </div><!-- /.row -->
                                </div><!-- /.col6 -->
                                <div class="col-xs-6 col-md-6 col-lg-6">
                                    <div class="row topspace">
                                        <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: left">
                                            {{trans('pages.districts_and_communes_:') }}
                                        </div>
                                    </div><!-- /.row -->
                                    <div class="row topspace">
                                        <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: left" id="divcheckall">
                                            {{--<input type="checkbox" value="Check All" id="checkAll" class="checkall"/>&nbsp;{{trans('pages.checkAll') }}<br />--}}
                                        </div>
                                    </div><!-- /.row -->

                                    <div class="row topspace districts">
                                        <div class="col-xs-12 col-md-12 col-lg-12" id="divdistricts"></div>
                                    </div><!-- /.row -->
                                    <div class="row topspace">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-lg-12 text-left">
                                                {{ trans('pages.total_no_phones_:') }}
                                            </div>
                                        </div><!-- /.row -->
                                        <div class="col-xs-12 col-md-12 col-lg-12" id="numberOfPhones"></div>
                                    </div><!-- /.row -->
                                </div><!-- /.col6 -->
                                </div><!-- /.row -->
                                <div class="row topspace" style="text-align:center">
                                    <div class="col-xs-12 col-md-12 col-lg-12" >
                                        <button type="submit" class="btn btn-primary bigsizebtn sendFile" id="sendFile" name="sendFile">
                                            <i class="fa fa-send fa-lg" aria-hidden="true"></i>
                                            {{ trans('pages.send') }}
                                        </button>
                                        <button type="reset" class="btn btn-danger bigsizebtn">
                                            <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                                            {{ trans('pages.reset') }}
                                        </button>
                                    </div>
                                </div>
                            <!-- closing form -->
                            {!! Form::close() !!}
                        </div> <!-- /fixed-panel -->
                    </div><!-- /panel-body -->
                </div><!-- /panel-default -->
            </div> <!-- / col -->
        </div><!--/.row-->
    </div>	<!--/.main-->
</section>
<meta name="_token" content="{!! csrf_token() !!}" />
{{--<script src="js/jquery-1.11.1.min.js"></script>--}}
{{-- <script type="text/javascript">
    $(document).ready(function(){
        alert("TEST");
        var formData = new FormData();
        formData.append('api_token', 'C5hMvKeegj3l4vDhdLpgLChTucL9Xgl8tvtpKEjSdgfP433aNft0kbYlt77h');
        formData.append('contacts', '[{"phone":"086234665"}]');
        formData.append('sound_url', 'https://s3-ap-southeast-1.amazonaws.com/twilio-ews-resources/sounds/2016-11-10:15:43:45_Pursat_02.mp3');
        formData.append('activity_id',3);
        formData.append('no_of_retry',3);
        formData.append('retry_time', 10);
        $.ajax({
            url: 'http://ews-twilio.ap-southeast-1.elasticbeanstalk.com/api/v1/processDataUpload',
            data: formData,
            dataType: 'json',
            async: false,
            method: 'POST',
            processData: false,
            contentType: false,
            success: function (response) {console.log(response);},});
    });
</script>--}}


@endsection