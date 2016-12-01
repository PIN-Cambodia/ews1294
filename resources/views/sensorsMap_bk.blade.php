@extends('layouts.master')

@section('content')
<!-- Services Section -->
{{--<section id="services">--}}
  <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
    <div class="row">
      <ol class="breadcrumb">
        <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
        <li class="active"> Home </li>
      </ol>
    </div><!--/.row-->
    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12" >
        {{--<iframe src="http://cambodiameteo.com/map?menu=3&lang=en" frameborder="0" allowfullscreen class="iframe-resp"></iframe>--}}
      </div>
    </div><!--/.row-->

    <div class="row">
      <div class="col-xs-12 col-md-12 col-lg-12" id="mapdiv">
        {{--<iframe src="http://cambodiameteo.com/map?menu=3&lang=en" frameborder="0" allowfullscreen class="iframe-resp"></iframe>--}}
      </div>
    </div><!--/.row-->
  </div>	<!--/.main-->
{{--</section>--}}

<script src="js/OpenLayers.js"></script>
<script>
  map = new OpenLayers.Map("mapdiv");
  //alert('map=' + map);
  map.addLayer(new OpenLayers.Layer.OSM());

  epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
  projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)

  var lonLat = new OpenLayers.LonLat( -0.1279688 ,51.5077286 ).transform(epsg4326, projectTo);


  var zoom=14;
  map.setCenter (lonLat, zoom);

  var vectorLayer = new OpenLayers.Layer.Vector("Overlay");

  // Define markers as "features" of the vector layer:
  var feature = new OpenLayers.Feature.Vector(
          new OpenLayers.Geometry.Point( -0.1279688, 51.5077286 ).transform(epsg4326, projectTo),
          {description:'This is the value of<br>the description attribute'} ,
          {externalGraphic: 'img/marker_red.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
  );
  vectorLayer.addFeatures(feature);

  var feature = new OpenLayers.Feature.Vector(
          new OpenLayers.Geometry.Point( -0.1244324, 51.5006728  ).transform(epsg4326, projectTo),
          {description:'Big Ben'} ,
          {externalGraphic: 'img/marker_red.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
  );
  vectorLayer.addFeatures(feature);

  var feature = new OpenLayers.Feature.Vector(
          new OpenLayers.Geometry.Point( -0.119623, 51.503308  ).transform(epsg4326, projectTo),
          {description:'London Eye'} ,
          {externalGraphic: 'img/marker_red.png', graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25  }
  );
  vectorLayer.addFeatures(feature);


  map.addLayer(vectorLayer);


  //Add a selector control to the vectorLayer with popup functions
  var controls = {
    selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
  };

  function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
            feature.geometry.getBounds().getCenterLonLat(),
            null,
            '<div class="markerContent">'+feature.attributes.description+'</div>',
            null,
            true,
            function() { controls['selector'].unselectAll(); }
    );
    //feature.popup.closeOnMove = true;
    map.addPopup(feature.popup);
  }

  function destroyPopup(feature) {
    feature.popup.destroy();
    feature.popup = null;
  }

  map.addControl(controls['selector']);
  controls['selector'].activate();

</script>



@endsection
