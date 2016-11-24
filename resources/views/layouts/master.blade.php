<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="_token" content="{{ csrf_token() }}" />
  <title> EWS - Emergency Warning System </title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  {{--<link href="css/datepicker3.css" rel="stylesheet">--}}
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/pe-icon-7-stroke.css" rel="stylesheet">
  <link href="css/custom.css" rel="stylesheet">

  @yield('datatable-css')

  <script src="js/jquery-1.11.1.min.js"></script>
  <script src="js/lumino.glyphs.js"></script>
  <script src="/js/jquery-waiting.js"></script>

  <!--Icons-->
  <!--[if lt IE 9]>
  <script src="/js/html5shiv.js"></script>
  <script src="/js/respond.min.js"></script>
  <![endif]-->

  </head>

  <body id="page-top" class="index">
    @include('layouts._navigation')
    @include('layouts._header')
    @yield('content')
    @yield('datatable-js')
    {{--@include('layouts._footer')--}}
  </body>
</html>
