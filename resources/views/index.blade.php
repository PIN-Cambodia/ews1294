@extends('layouts.master')
@section('content')
<!-- Services Section -->
<section id="services">
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
<li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> {{ trans('menus.home') }} </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12">
        <iframe src="http://www.cambodiameteo.com/slideshow?menu=117&lang=en&domain=CAMBODIA" frameborder="0" allowfullscreen class="iframe-resp"></iframe>
      </div>
    </div><!--/.row-->
  </div><!--/.main-->
</section>

<script>
  $(document).ready(function() {
    fitIframe();
    $(window).resize(function() {
      fitIframe();
    });
    
  });
  function fitIframe() {
    /* $('#sidebar-collapse').height()-90 : means take height of sidebar-collapse - the height of header and footer  */
    $('iframe').css("min-height", $('#sidebar-collapse').height()-(50+$('#footer').height()));
  }
</script>
@endsection
