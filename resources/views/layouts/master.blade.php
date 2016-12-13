<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> EWS - Emergency Warning System </title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/font-awesome.min.css" rel="stylesheet">
  <link href="css/datepicker3.css" rel="stylesheet">
  <link href="css/styles.css" rel="stylesheet">
  <link href="css/pe-icon-7-stroke.css" rel="stylesheet">
  <link rel="stylesheet" type="text/css" href="{{asset('js/sweetalert-master/dist/sweetalert.css')}}">
  <!-- datatable css -->
  <link href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css" rel="stylesheet"
        xmlns="http://www.w3.org/1999/html"/>
  <link href="//cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css" rel="stylesheet" />

  <link href="css/custom.css" rel="stylesheet">


    @yield('datatable-css')

    <script src="js/jquery-1.11.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/lumino.glyphs.js"></script>
    <script src="/js/jquery-waiting.js"></script>

    <!--datatable JS -->
    <script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>

    <!--Icons-->
    <!--[if lt IE 9]>
    <script src="/js/html5shiv.js"></script>
    <script src="/js/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('js/ajax-district.js')}}"></script>
    <script src="{{asset('js/sweetalert-master/dist/sweetalert.min.js')}}"></script>

  </head>

  <body id="page-top" class="index no-overflow">
  @include('layouts._navigation')
    @include('layouts._header')
    @yield('content')
    @yield('datatable-js')
    @include('layouts._footer')
  </body>
  <script>
    $(document).ready(function() {
      fitFixPanel();
      $(window).resize(function() {
        fitFixPanel();
      });
    });
    function fitFixPanel() {
      /* $('#sidebar-collapse').height()-90 : means take height of sidebar-collapse - the hight of header and footer  */
      $('.fixed-panel').css("max-height", $('#sidebar-collapse').height()-(100+$('#footer').height()));
    }
  </script>
</html>
