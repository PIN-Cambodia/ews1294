<nav class="navbar navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="{{ url('/') }}">
        <div class="row">
          <div class="col-sm-3 col-md-3 col-lg-3">
            <img src="/logo.png" width="80">
          </div>
          <div class="col-sm-9 col-md-9 col-lg-9" style="margin-top: 5px; text-align: center">
              <h4><b>EARLY<br>WARNING<br> SYSTEM</b></h4>
              <h4><b> {{ trans('menus.ews_header_str2') }} </b></h4>
          </div>
        </div>
      </a>
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- or if(Auth::user()) -->
      @if(Auth::check())
        <ul class="user-menu">
          <li class="dropdown pull-right">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <svg class="glyph stroked male-user">
                <use xlink:href="#stroked-male-user"></use>
              </svg>
              {{ Auth::user()->name }}
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="logout">
                  <svg class="glyph stroked cancel">
                    <use xlink:href="#stroked-cancel"></use>
                  </svg>
                  {{ trans('menus.logout') }}
                </a>
              </li>
            </ul>
          </li>
        </ul>
      @endif
      <form class="form-horizontal" role="form" method="POST" action="{{ url('/changelang') }}">
      {{ csrf_field() }}
      <!-- Display language flag in contrast of current locale-->
        @if (App::getLocale()=='km')
          <ul class="user-menu"><li><button class="btnNoBtnBackground" type="submit" value="en" name="flag_icon"> <img src="/engflag.png"/> </button></li></ul>
        @else
          <ul class="user-menu"><li><button class="btnNoBtnBackground" type="submit" value="km" name="flag_icon"> <img src="/kmflag.png"/> </button></li></ul>
        @endif
      </form>

    </div><!-- /.navbar-header -->
  </div><!-- /.container-fluid -->
</nav>
