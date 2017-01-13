@extends('layouts.master')
@section('content')
<section id="uploadsoundfile">
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                <li class="active"> {{ trans('menus.upload_sound_file') }} </li>
            </ol>
        </div>
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
                                                    @if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
                                                        <option value="AllProvinces"> {{ trans('pages.all_provinces') }} </option>
                                                    @endif
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
                                        </div>
                                </div>
                                <div class="col-xs-6 col-md-6 col-lg-6">
                                    <div class="row topspace">
                                        <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: left">
                                            {{trans('pages.districts_and_communes_:') }}
                                        </div>
                                    </div>
                                    <div class="row topspace">
                                        <div class="col-xs-12 col-md-12 col-lg-12" style="text-align: left" id="divcheckall">
                                        </div>
                                    </div>

                                    <div class="row topspace districts">
                                        <div class="col-xs-12 col-md-12 col-lg-12" id="divdistricts"></div>
                                    </div>
                                    <div class="row topspace">
                                        <div class="row">
                                            <div class="col-xs-12 col-md-12 col-lg-12 text-left">
                                                {{ trans('pages.total_no_phones_:') }}
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-md-12 col-lg-12" id="numberOfPhones"></div>
                                    </div>
                                </div>
                                </div>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--show waiting loading dialog -->
    <div class="modal fade fixed-dialog-center" id="modal_waiting" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog">
            <div><h3>{{ trans('sensors.waiting_dialog') }}</h3></div><br/>
            <div class="spinner"></div>
        </div>
    </div>

</section>
<meta name="_token" content="{!! csrf_token() !!}" />
@endsection