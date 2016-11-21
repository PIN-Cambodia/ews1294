@extends('layouts.master')

@section('content')
    <!-- Services Section -->
    <section id="services">
        <!-- Opening a form -->
        @if(Session::has('message'))
            <p class="alert-danger">{{Session::get('message')}}</p>
        @endif
        {!! Form::open(array('route' =>'call.them', 'method'=>'post','id'=>'uploadForm')) !!}

        <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
            <div class="row">
                <ol class="breadcrumb">
                    <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
                    <li class="active"> {{ trans('menus.upload_sound_file') }} </li>
                </ol>
            </div><!--/.row-->
            <div class="row topspace">
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
                    </div>

                    <div class="row topspace">
                        <div class="col-xs-4 col-md-4 col-lg-4">
                            {{ trans('pages.sound_file_:') }}
                        </div>
                        <div class="col-xs-8 col-md-8 col-lg-8">
                            <input type="file" name="soundFile" id="soundFile"><br />
                            <!-- <input type="file" name="phoneContactFile" id="phoneContactFile" class="hideContactFile"> -->
                        </div>
                    </div>

                </div>
                <div class="col-xs-6 col-md-6 col-lg-6">

                    <div class="row topspace">
                        <div class="col-xs-12 col-md-12 col-lg-12">
                            {{ trans('pages.districts_and_communes_:') }}
                        </div>

                    </div>
                    <div class="col-md-12" id="aa"></div><br>
                    <div class="row topspace districts">

                        <div class="col-xs-12 col-md-12 col-lg-12" id="divdistricts">

                        </div>

                        <!-- <input type="checkbox" value="testCHB" class="testDis" id="t">eee<br>
                        <input id="70403" class="commune" type="checkbox" value="70403">
              <span>ស្នាយអញ្ជិត (Snay' Anhchit)</span> -->
                        <br>
                    </div>

                    <div class="row topspace rg">
                        <div class="col-xs-12 col-md-12 col-lg-12" id="numberOfPhones">

                        </div>
                    </div>

                </div>
            </div><!--/.row-->
            <div class="row topspace">
                <br><br>
                <!-- <ol class="breadcrumb"> -->
                <!-- <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li> -->
                <center>
                    <button type="submit" class="btn btn-primary bigsizebtn sendFile" id="sendFile" name="sendFile">
                        <i class="fa fa-send fa-lg" aria-hidden="true"></i>
                        {{ trans('pages.send') }}
                    </button>
                    <button type="reset" class="btn btn-danger bigsizebtn">
                        <i class="fa fa-refresh fa-lg" aria-hidden="true"></i>
                        {{ trans('pages.reset') }}
                    </button>


                    {{--{{ Form::submit('  Call  ', array('class' => 'button','id'=>'deletebtn', 'onclick' => 'swal(\'Ajax request finished!\');')) }} --}}
                    {{--<input type="button" name="resetFle" value="Reset" class="button">--}}

                    {{--samak previous code--}}
                    {{--<input type="submit" name="sendFile" value="{{ trans('pages.send') }}" class="btn btn-primary bigsizebtn sendFile" id="sendFile">
                    {{ Form::reset(trans('pages.reset'), ['class' => 'btn btn-danger bigsizebtn']) }}--}}
                </center>
                <br>
                <!-- </ol> -->
            </div><!--/.row-->
        </div>	<!--/.main-->
        <!-- closing form -->
        {!! Form::close() !!}
    </section>

    <meta name="_token" content="{!! csrf_token() !!}" />
    <script src="{{asset('js/ajax-district.js')}}"></script>
    <script src="{{asset('js/sweetalert-master/dist/sweetalert.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('js/sweetalert-master/dist/sweetalert.css')}}">
@endsection
