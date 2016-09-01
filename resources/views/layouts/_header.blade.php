<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#sidebar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><span> EWS </span>(Emergency Warning System)</a>
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
            <!-- <li>
              <a href="#">
                <svg class="glyph stroked male-user">
                  <use xlink:href="#stroked-male-user"></use>
                </svg>
                 {{ trans('menus.profile') }}
               </a>
            </li> -->
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

    </div>
  </div><!-- /.container-fluid -->
</nav>
