<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <ul class="nav menu">
    <!-- Home Menu -->
    <li class="<?php if (preg_match("/home/i", Request::url())) echo "active"; else echo "";?>">
      <a href="home">
        <i class="pe-7s-home pe-lg"></i> {{ trans('menus.home') }}
       </a>
    </li>
    <!-- Upload Sound File Menu -->
    @role(['admin','NCDM','PCDM'])
    <li class="<?php if (preg_match("/soundFile/i", Request::url())) echo "active"; else echo "";?>">
      <a href="soundFile">
        <i class="pe-7s-speaker pe-lg"></i> {{ trans('menus.upload_sound_file') }}
      </a>
    </li>
   {{-- CallLog Report Menu--}}
    <li class="<?php if (preg_match("/calllogreport/i", Request::url())) echo "active"; else echo "";?>">
      <a href="calllogreport">
        <i class="pe-7s-graph2 pe-lg"></i> {{ trans('menus.calllog_report') }}
      </a>
    </li>
    @endrole

    @role(['admin'])
    <!-- User Management Menu -->
    <li class="<?php if (preg_match("/allusers/i", Request::url())) echo "active"; else echo "";?>">
      <a href="allusers">
        <i class="pe-7s-users pe-lg"></i>
         {{ trans('menus.users') }}
      </a>
    </li>
    @endrole

    <!-- Login Menu -->
    @if(!Auth::user())
    <li class="<?php if (preg_match("/login/i", Request::url())) echo "active"; else echo "";?>">
      <a href="login">
        <i class="pe-7s-user pe-lg"></i>
         {{ trans('auth.login') }}
      </a>
    </li>
    @endif
    <!-- Register Menu -->
    @role(['admin'])
    <li class="<?php if (preg_match("/register/i", Request::url())) echo "active"; else echo "";?>">
      <a href="register">
        <i class="pe-7s-add-user pe-lg"></i>
         {{ trans('auth.register') }}
       </a>
    </li>
    @endrole

    @role(['admin'])
    <!-- Upload Sound File Menu -->
    <li class="<?php if (preg_match("/wiki/i", Request::url())) echo "active"; else echo "";?>">
      <a href="wiki">
        <i class="pe-7s-notebook pe-lg"></i> {{ trans('menus.api_wiki') }}
      </a>
    </li>
    @endrole

  <!-- sensor map management -->
    <li class="<?php if (preg_match("/sensormap/i", Request::url())) echo "active"; else echo "";?>">
      <a href="sensormap">
        <i class="pe-7s-map-2 pe-lg"></i> {{ trans('sensors.sensor_map') }}
      </a>
    </li>

  @role(['admin'])
    <!--  sensor  -->
    <!-- sensor management -->
    <li class="<?php if (preg_match("/sensors/i", Request::url())) echo "active"; else echo "";?>">
      <a href="sensors">
        <i class="pe-7s-signal pe-lg"></i> {{ trans('menus.sensors_mgt') }}
      </a>
    </li>
    <!-- sensor trigger management -->
    <li class="<?php if (preg_match("/sensortrigger/i", Request::url())) echo "active"; else echo "";?>">
      <a href="sensortrigger">
        <i class="pe-7s-tools pe-lg"></i> {{ trans('sensors.sensor_trigger_mgmt') }}
      </a>
    </li>
    @endrole
    <!-- about us -->

    <li class="<?php  if (preg_match("/about/i", Request::url())) echo "active"; else echo ""; ?>">
      <a href="about">
        <i class="pe-7s-users pe-lg"></i> {{ trans('menus.about_us') }}
       </a>
    </li>
<!-- contact us page  -->
    <li class="<?php  if (preg_match("/contact/i", Request::url())) echo "active"; else echo ""; ?>">
      <a href="contact">
        <i class="pe-7s-mail-open pe-lg"></i> {{ trans('menus.contact_us') }}
       </a>
    </li>
  </ul>
</div><!--/.sidebar-->
