<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <!-- <form role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
  </form> -->

  <ul class="nav menu">

    <!-- <li class="active"><a href=""><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Home </a></li>
    <li><a href=""><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg></a></li> -->
    <!-- <li><a href=""><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg> </a></li> -->

    <li class="<?php if (preg_match("/home/i", Request::url())) echo "active"; else echo "";?>"><a href="home"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Home </a></li>
    <li class="<?php if (preg_match("/soundFile/i", Request::url())) echo "active"; else echo "";?>"><a href="soundFile"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg>Upload Sound File</a></li>
    <!-- <li><a href=""><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked star"><use xlink:href="#stroked-star"></use></svg> </a></li> -->
    <li role="presentation" class="divider"></li>
    <li><a href="login"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Login</a></li>
    <li><a href="register"><svg class="glyph stroked add-single-female-user"><use xlink:href="#stroked-add-single-female-user"></use></svg> Register</a></li>
  </ul>

</div><!--/.sidebar-->
