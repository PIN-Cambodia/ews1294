@extends('layouts.master')

@section('content')
<!-- Services Section -->
<section id="services">
<!-- <form> -->
{!! Form::open(array('route' => 'phones.insert')) !!}
<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
  <div class="row">
    <ol class="breadcrumb">
      <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
      <li class="active"> {{ trans('menus.get_phones_from_call_log_registration') }}</li>
    </ol>
  </div><!--/.row-->
  <div class="row topspace">
    <div class="col-xs-3 col-md-3 col-lg-3">
      Reminder Group:
    </div>
     <div class="col-xs-9 col-md-9 col-lg-9">
        <select name="rg_name" id="reminderGroup">
                <option value="AllProvinces">All reminder groups</option>
                @foreach ($reminderGroups as $reminderGroup)
                     <option value="{{ $reminderGroup->CCode }}#;{{ $reminderGroup->CReminderGroup }}">{{ $reminderGroup->CReminderGroup }}</option>
                @endforeach
        </select>
      <br>
    </div>

    <!-- <ol class="breadcrumb"> -->
      <!-- <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li> -->
      <br><br><br>
       <center>

         <input type="submit" name="sendFile" value="Send" class="button">
         <input type="reset" name="resetFle" value="Reset" class="button">
       </center>
    <br>
    <!-- </ol> -->
  </div><!--/.row-->
</div>	<!--/.main-->
<!-- </form> -->
{!! Form::close() !!}
</section>
<meta name="_token" content="{!! csrf_token() !!}" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="{{asset('js/ajax-district.js')}}"></script>
@endsection
