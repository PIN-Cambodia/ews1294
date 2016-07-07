<div id="sidebar-collapse" class="col-sm-3 col-lg-2 sidebar">
  <!-- <form role="search">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="Search">
    </div>
  </form> -->

  <ul class="nav menu">
    <li class="<?php if (preg_match("/home/i", Request::url())) echo "active"; else echo "";?>"><a href="home"><svg class="glyph stroked dashboard-dial"><use xlink:href="#stroked-dashboard-dial"></use></svg> Home </a></li>
    <li class="<?php if (preg_match("/soundFile/i", Request::url())) echo "active"; else echo "";?>"><a href="soundFile"><svg class="glyph stroked calendar"><use xlink:href="#stroked-calendar"></use></svg>Upload Sound File</a></li>
    <li class="<?php if (preg_match("/getPhonesFromReminderGroup/i", Request::url())) echo "active"; else echo "";?>"><a href="getPhonesFromReminderGroup"><svg class="glyph stroked line-graph"><use xlink:href="#stroked-line-graph"></use></svg>Get Phones From CallLogs</a></li>
    <li class="<?php if (preg_match("/extractTargetPhones/i", Request::url())) echo "active"; else echo "";?>"><a href="extractTargetPhones"><svg class="glyph stroked table"><use xlink:href="#stroked-table"></use></svg> Target Phones </a></li>
    <li><a href=""><svg class="glyph stroked pencil"><use xlink:href="#stroked-pencil"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked app-window"><use xlink:href="#stroked-app-window"></use></svg> </a></li>
    <li><a href=""><svg class="glyph stroked star"><use xlink:href="#stroked-star"></use></svg> </a></li>
    <li role="presentation" class="divider"></li>
    <li><a href="login.html"><svg class="glyph stroked male-user"><use xlink:href="#stroked-male-user"></use></svg> Login</a></li>
  </ul>

</div><!--/.sidebar-->
