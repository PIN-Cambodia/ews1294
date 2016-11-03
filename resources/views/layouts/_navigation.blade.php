<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <!-- <form role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
  </form> -->

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
        <i class="pe-7s-cloud-upload pe-lg"></i> {{ trans('menus.upload_sound_file') }}
      </a>
    </li>
    <!-- Get Phones From Call Logs Menu -->
    <li class="<?php if (preg_match("/extractTargetPhones/i", Request::url())) echo "active"; else echo "";?>">
      <a href="extractTargetPhones">
        <i class="pe-7s-call pe-lg"></i>
        {{ trans('menus.get_phones_from_call_log') }}
       </a>
    </li>

    <!-- Target Phones Menu -->
    <!-- <li class="<?php //if (preg_match("/extractTargetPhones/i", Request::url())) echo "active"; else echo "";?>">
      <a href="extractTargetPhones">
        <svg class="glyph stroked table">
          <use xlink:href="#stroked-table"></use>
        </svg>
         Target Phones
       </a>
    </li>

   -->
      <!-- CallLog Report Menu -->
      <li class="<?php if (preg_match("/calllogreport/i", Request::url())) echo "active"; else echo "";?>">
          <a href="calllogreport">
              <i class="pe-7s-graph2 pe-lg"></i>
              {{ trans('menus.calllog_report') }}
          </a>
      </li>
      @endrole

    @role(['admin','NCDM'])
    <!-- User Management Menu -->
    <li class="<?php if (preg_match("/allusers/i", Request::url())) echo "active"; else echo "";?>">
      <a href="allusers">
        <i class="pe-7s-users pe-lg"></i>
         {{ trans('menus.users') }}
      </a>
    </li>
    @endrole

    <li role="presentation" class="divider"></li>
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
    @role(['admin','NCDM'])
    <li class="<?php if (preg_match("/register/i", Request::url())) echo "active"; else echo "";?>">
      <a href="register">
        <i class="pe-7s-add-user pe-lg"></i>
         {{ trans('auth.register') }}
       </a>
    </li>
    @endrole

    @role(['admin'])
    <!-- Upload Sound File Menu -->
    <li class="<?php if (preg_match("/apiWiki/i", Request::url())) echo "active"; else echo "";?>">
      <a href="wiki">
        <i class="pe-7s-notebook pe-lg"></i> {{ trans('menus.api_wiki') }}
      </a>
    </li>
    @endrole


    <!-- <li class="active"><a href=""><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Home </a></li>
    <li><a href=""><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg></a></li> -->
    <!-- <li><a href=""><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg> </a></li> -->
    <!-- <li><a href=""><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked star"><use xlink:href="#stroked-star"></use></svg> </a></li> -->

  </ul>

</div><!--/.sidebar-->
