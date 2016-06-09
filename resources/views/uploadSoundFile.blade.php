@extends('layouts.master')

@section('content')
<!-- Services Section -->
<section id="services">
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
          <select>
               <option value="AllProvinces">All provinces</option>
               <option value="Province 1">Province 1</option>
               <option value="Province 2">Province 2</option>
               <option value="Province 3">Province 3</option>
               <option value="Province 4">Province 4</option>
          </select>
        </div>
      </div>

      <div class="row topspace">
        <div class="col-xs-3 col-md-3 col-lg-3">
          Sound File:
        </div>
        <div class="col-xs-9 col-md-9 col-lg-9">
          <input type="file" name="soundFile">
        </div>
      </div>

      <div class="row topspace">
        <div class="col-xs-12 col-md-12 col-lg-12">
          Phone number to call:
        </div>
      </div>

      <div class="row topspace phones">
        <div class="col-xs-12 col-md-12 col-lg-12">
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />
          <input type="checkbox" /> 012555555 <br />

        </div>
      </div>

    </div>
    <div class="col-xs-6 col-md-6 col-lg-6">

      <div class="row topspace">
        <div class="col-xs-12 col-md-12 col-lg-12">
          Districts and Communes:
        </div>
      </div>

      <div class="row topspace districts">
          <div class="col-xs-12 col-md-12 col-lg-12">
              <input type="checkbox" /> District A <br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <input type="checkbox" />  District B <br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <input type="checkbox" />  District C <br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
              <span class="communes"><input type="checkbox" /> This is checkbox </span><br />
          </div>
      </div>

    </div>
  </div><!--/.row-->
  <div class="row topspace">
    <br><br>
    <!-- <ol class="breadcrumb"> -->
      <!-- <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li> -->
       <center>
         <input type="button" name="sendFile" value="Send" class="button">
         <input type="button" name="resetFle" value="Reset" class="button">
       </center>
    <br>
    <!-- </ol> -->
  </div><!--/.row-->
</div>	<!--/.main-->
</section>

@endsection
