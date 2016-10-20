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
      <li class="active"> Upload Sound File Here</li>
    </ol>
  </div><!--/.row-->
  <div class="row topspace">
    <div class="col-xs-6 col-md-6 col-lg-6">
      <div class="row topspace">
        <div class="col-xs-3 col-md-3 col-lg-3">
          Province:
        </div>
         <div class="col-xs-9 col-md-9 col-lg-9">
            <select name="province_id" id="province">
                    <option value="AllProvinces">All provinces</option>
                    @foreach ($provinces as $item)
                         <option value="{{ $item->PROCODE }}">{{ $item->PROVINCE_KH }}</option>
                    @endforeach
            </select>

        </div>
      </div>

      <div class="row topspace">
        <div class="col-xs-3 col-md-3 col-lg-3">
          Sound File:
        </div>
        <div class="col-xs-9 col-md-9 col-lg-9">
          <input type="file" name="soundFile" id="soundFile"><br />
          <!-- <input type="file" name="phoneContactFile" id="phoneContactFile" class="hideContactFile"> -->
        </div>
      </div>

    </div>
    <div class="col-xs-6 col-md-6 col-lg-6">

      <div class="row topspace">
        <div class="col-xs-12 col-md-12 col-lg-12">
          Districts and Communes:
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
         <input type="submit" name="sendFile" value="Send" class="button sendFile" id="sendFile">
         <!-- {{ Form::submit('  Call  ', array('class' => 'button','id'=>'deletebtn', 'onclick' => 'swal(\'Ajax request finished!\');')) }} -->
         {{--<input type="button" name="resetFle" value="Reset" class="button">--}}
           {{ Form::reset('Reset', ['class' => 'button']) }}
       </center>
    <br>
    <!-- </ol> -->
  </div><!--/.row-->
</div>	<!--/.main-->
<!-- closing form -->
{!! Form::close() !!}
</section>
<meta name="_token" content="{!! csrf_token() !!}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{asset('js/ajax-district.js')}}"></script>
<script src="{{asset('js/sweetalert-master/dist/sweetalert.min.js')}}"></script>
<link rel="stylesheet" type="text/css" href="{{asset('js/sweetalert-master/dist/sweetalert.css')}}">
@endsection
