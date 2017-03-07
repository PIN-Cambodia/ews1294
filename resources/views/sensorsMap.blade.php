@extends('layouts.master')
@section('content')
    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      <div class="row">
        <ol class="breadcrumb">
          <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
          <li class="active"> Home </li>
        </ol>
      </div><!--/.row-->
      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" id="mapdiv">
        </div>
      </div><!--/.row-->
    </div>	<!--/.main-->

{{--@endsection--}}
<link rel="stylesheet" href="http://dev.openlayers.org/theme/default/style.css" type="text/css">
<link rel="stylesheet" href="http://dev.openlayers.org/examples/style.css" type="text/css">
<script src="/js/OpenLayers.js"></script>
<script>
  // Use OpenLayers library to display Map
  map = new OpenLayers.Map("mapdiv");
  map.addLayer(new OpenLayers.Layer.OSM());
  epsg4326 =  new OpenLayers.Projection("EPSG:4326"); //WGS 1984 projection
  projectTo = map.getProjectionObject(); //The map projection (Spherical Mercator)
  var lonLat = new OpenLayers.LonLat( 106.00,11.9000 ).transform(epsg4326, projectTo);
  var zoom=8;
  map.setCenter (lonLat, zoom);
  var vectorLayer = new OpenLayers.Layer.Vector("Overlay");
  // Foreach sensor, display marker on Map
  // and allow user to click on sensor to display its sensor log data (stream height).
  @foreach($sensors as $sensor)
    var imgSensor="img/marker_black.png";
    var feature = new OpenLayers.Feature.Vector(
          new OpenLayers.Geometry.Point( {{$sensor['location_coordinates'] }} ).transform(epsg4326, projectTo),
          {description:'<b>{{trans('sensors.alert_header')}}</b><br><b>{{ trans('sensors.popup_label') }} {{$sensor['sensor_id']}}</b><br><p><a href ="/sensorsLog20?sensor_id={{$sensor['sensor_id']}}"><b>{{ trans('sensors.sensorlog24') }}</b> </a><br><a href ="/sensorsLog1thReadingOf30days?sensor_id={{$sensor['sensor_id']}}"><b>{{ trans('sensors.sensorlog1threadingOf30days') }} </a>'} ,
          {
              externalGraphic: imgSensor, graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25}
    );
    vectorLayer.addFeatures(feature);
  // Display marker with different colors according to stream height getting from each sensor.
  // yellow is warning; red is emergency; green is normal; and black is not getting data within 24 hrs.
    @if(!empty($sensors24hrs))
        @foreach($sensors24hrs as $sensor24)
          @if($sensor['sensor_id'] == $sensor24->sensor_id)
                @if($sensor24->stream_height >= $sensor24->warning_level && $sensor24->stream_height < $sensor24->emergency_level)
                    imgSensor='img/marker_yellow.png';
                @elseif($sensor24->stream_height >= $sensor24->emergency_level)
                    imgSensor='img/marker_red.png';
                @elseif($sensor24->stream_height < $sensor24->warning_level)
                    imgSensor='img/marker_green.png';
                @endif
                var feature = new OpenLayers.Feature.Vector(
                    new OpenLayers.Geometry.Point( {{$sensor24->location_coordinates }} ).transform(epsg4326, projectTo),
                    {description:'<b>Show Report of Sensor ID: {{$sensor24->sensor_id}}</b><br><p><a href ="/sensorsLog20?sensor_id={{$sensor24->sensor_id }}"> <b>{{ trans('sensors.sensorlog24') }}</b> </a><br><a href ="/sensorsLog1thReadingOf30days?sensor_id={{$sensor24->sensor_id }}"><i class="fa fa-btn fa-arrow-right "></i> <b>{{ trans('sensors.sensorlog1threadingOf30days') }}</b> </a>'} ,
                    {
                      externalGraphic: imgSensor, graphicHeight: 25, graphicWidth: 21, graphicXOffset:-12, graphicYOffset:-25}
                    );
                vectorLayer.addFeatures(feature);
          @endif
        @endforeach
    @endif
  @endforeach
  map.addLayer(vectorLayer);
  //Add a selector control to the vectorLayer with popup functions
  var controls = {
    selector: new OpenLayers.Control.SelectFeature(vectorLayer, { onSelect: createPopup, onUnselect: destroyPopup })
  };

  // Function to create popup on Map
  function createPopup(feature) {
    feature.popup = new OpenLayers.Popup.FramedCloud("pop",
            feature.geometry.getBounds().getCenterLonLat(),
            null,
            '<div class="markerContent">'+feature.attributes.description+'</div>',
            null,
            true,
            function() { controls['selector'].unselectAll(); }
    );
    map.addPopup(feature.popup);
  }

  // Function to destroy popup on Map
  function destroyPopup(feature) {
    feature.popup.destroy();
    feature.popup = null;
  }

  map.addControl(controls['selector']);
  controls['selector'].activate();

</script>
@endsection