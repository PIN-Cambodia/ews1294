@extends('layouts.master')

    <div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
      <div class="row">
        <ol class="breadcrumb">
          <li><a href="#"><svg class="glyph stroked home"><use xlink:href="#stroked-home"></use></svg></a></li>
          <li class="active"> Home </li>
        </ol>
      </div><!--/.row-->
      <div class="row">
        <div class="col-xs-12 col-md-12 col-lg-12" id="map">
        </div>
      </div><!--/.row-->
    </div>  <!--/.main-->


  <style>
    #map { width: 100%; height: 90%; }
    .info { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
    .legend { text-align: left; line-height: 18px; color: #555; } .legend i { width: 18px; height: 18px; float: left; margin-right: 8px; opacity: 0.7; }
      #iframe {
      width:600px;
      height: 340px;
    }
  </style>
  

 {{--@endsection--}}
  <link rel="stylesheet" href="../css/leaflet.css"/>
  <script src="https://unpkg.com/leaflet@1.3.1/dist/leaflet.js" integrity="sha512-/Nsx9X4HebavoBvEBuyp3I7od5tA0UzAxs+j83KgC8PU0kgB4XiK4Lfe4y4cgBtaRJQEIFCW+oC506aPT2L1zw==" crossorigin=""></script>
  <script src="https://code.jquery.com/jquery-2.1.3.js" ></script>
<script>



var sensorData = {};
sensorData['type'] = 'FeatureCollection';
sensorData['features'] = [];


@foreach($sensors as $sensor)
  var sensorStatus = "Inactive"; //default or if no data within last 24hr  
  var recentHeight = "-1"; //store the most recent value, -1 if none
  //determine the sensors status into a var
  //only the most RECENT record in last 24hr is returned.
 @if(!empty($sensors24hrs))
        @foreach($sensors24hrs as $sensor24)
          @if($sensor['sensor_id'] == $sensor24->sensor_id)
          recentHeight = {{$sensor24->stream_height}};
                @if($sensor24->stream_height >= $sensor24->warning_level && $sensor24->stream_height < $sensor24->emergency_level)
                    sensorStatus="Warning";
                @elseif($sensor24->stream_height >= $sensor24->emergency_level)
                    sensorStatus="Alert";
                @elseif($sensor24->stream_height < $sensor24->warning_level)
                    sensorStatus="Normal";
                @endif
          @endif
        @endforeach
  @endif

  var newFeature = {
    "type": "Feature",
    "geometry": {
      "type": "Point",
      "coordinates": [{{$sensor['location_coordinates'] }}]
    },
    "properties": {
      "name": "{{$sensor['additional_location_info']}}",
      "sensorID": "{{$sensor['sensor_id']}}",
      "waterlevel": recentHeight,
      "status": sensorStatus
    }
  }
  sensorData['features'].push(newFeature);

  //loop to the next sensor
  @endforeach

 
var mbAttr = 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, ' +
      '<a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
      'Imagery © <a href="http://mapbox.com">Mapbox</a>',
    mbUrl = 'https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw';
  
var grayscale   = L.tileLayer(mbUrl, {id: 'mapbox.light', attribution: mbAttr}),
  streets  = L.tileLayer(mbUrl, {id: 'mapbox.streets',   attribution: mbAttr});
  sattelite  = L.tileLayer(mbUrl, {id: 'mapbox.satellite',   attribution: mbAttr});

var baseLayers = {
  "Grayscale": grayscale,
  "Streets": streets,
  "Sattelite": sattelite
};

//initialize the map
var map = L.map('map', {
    center: [12.4031626,105.7709708],
    zoom: 7,
    layers: [streets]})
    
//map switcher control
var mapSwitcher = L.control.layers(baseLayers);
  mapSwitcher.setPosition('bottomleft');
  mapSwitcher.addTo(map);

//define icon classes.
var greenIcon = L.icon({
    iconUrl: 'img/greenIcon.png',
    shadowUrl: 'img/marker-shadow.png',
    iconAnchor:   [25, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var yellowIcon = L.icon({
    iconUrl: 'img/yellowIcon.png',
    shadowUrl: 'img/marker-shadow.png',
    iconAnchor:   [25, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [-12.5, -41] // point from which the popup should open relative to the iconAnchor
});

var redIcon = L.icon({
    iconUrl: 'img/redIcon.png',
    shadowUrl: 'img/marker-shadow.png',
    iconAnchor:   [25, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var greyIcon = L.icon({
    iconUrl: 'img/greyIcon.png',
    shadowUrl: 'img/marker-shadow.png',
    iconAnchor:   [25, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});

var selectedIcon = L.icon({
    iconUrl: 'img/blueIcon.png',
    shadowUrl: 'img/marker-shadow.png',
    iconAnchor:   [25, 41], // point of the icon which will correspond to marker's location
    popupAnchor:  [-12.5, -41] // point from which the popup should open relative to the iconAnchor
});

var legend = L.control({position: 'bottomright'});

//get the correct icon depending on sensor status.
function getIcon(d) {
  return d == 'Inactive' ? greyIcon :
      d == 'Inactive_selected' ? selectedIcon :
      d == 'Normal'  ? greenIcon :
      d == 'Normal_selected' ? selectedIcon :
      d == 'Warning' ? yellowIcon :
      d == 'Warning_selected' ? selectedIcon :
      d == 'Alert' ?   redIcon:
      d == 'Alert_selected' ? selectedIcon :
                greyIcon;
      
}
//get the correct icon for each legend item.
function getLegendIcon(d) {
  return d == 'Inactive' ? "img/greyIcon.png" :
      d == 'Normal'  ? "img/greenIcon.png" :
      d == 'Warning'  ? "img/yellowIcon.png" :
      d == 'Alert' ? "img/redIcon.png" :
                "img/greyIcon.png" ;
      
}

function onEachFeature(feature, layer) {
  layer.on({
    mouseover: highlightFeature,
    mouseout: resetHighlight,
    click: showPopUp //zoomToFeature
  });
}

function highlightFeature(e) {
  //console.log("Item highlighted.")
  e.target.setIcon(getIcon(e.target.feature.properties.status.toString() + "_selected"));
  info.update(e.target.feature.properties);
  
 };

function resetHighlight(e) {
  //console.log("Item De-highlighted.")
  e.target.setIcon(getIcon(e.target.feature.properties.status));
  info.update();
}

function zoomToFeature(e) {
  map.setView(e.target.getLatLng(), 9);
}

var popup = L.popup()
    .setContent("No popup info :(");
    
function showPopUp(e) {
   popup.setContent("<iframe id='iframe'  frameBorder=none' scrolling='no' src='sensorsEChartPopup?sensor_id=" + e.target.feature.properties.sensorID + "&type=1'></iframe><br><a href ='/sensorsLog20?sensor_id=" + e.target.feature.properties.sensorID + "'><b>View Table - Last 6 hours</b></a><br><a href ='/sensorsLog1thReadingOf30days?sensor_id=" + e.target.feature.properties.sensorID + "'><b>View Table -  Last 30 Days</a>" );

    // + "<iframe id='iframe'  frameBorder='0' scrolling='yes' src='sensors6hrs?sensor_id=" + e.target.feature.properties.sensorID + "&type=1'></iframe>");  //"You clicked on " + e.target.feature.properties.name.toString() + 
   popup.setLatLng(e.target.getLatLng)
   e.target.unbindPopup(); //solves issue of popup not re-opening a second time after being closed.
   e.target.bindPopup(popup, {maxWidth: "auto"}).openPopup();
}

// control that shows state info on hover
var info = L.control();

info.onAdd = function (map) {
  this._div = L.DomUtil.create('div', 'info');
  this.update();
  return this._div;
};

info.update = function (props) {
  this._div.innerHTML = '' +  (props ?
    '<b>Name: </b> ' + props.name + '<br /><b>Sensor ID:</b> ' + props.sensorID + '<br /> <b>Latest Depth:</b> ' + props.waterlevel + 'cm <br /> <b>Status:</b> ' 
      + props.status : 'Hover over a sensor');
};

info.addTo(map);

legend.onAdd = function (map) {

    var div = L.DomUtil.create('div', 'info legend'),
        grades = ["Inactive", "Normal", "Warning", "Alert"],
        labels = [];
   
   for (var i = 0; i < grades.length; i++) {
      labels.push(
        '<img src="' + getLegendIcon((grades[i])) + '"  height="20.5px" width="12.5px">' + grades[i] );
    }

    div.innerHTML = '<h4>Sensor Status</h4>' + labels.join('<br>');
    return div;
};

legend.addTo(map);

geojson = L.geoJson(sensorData, {
  onEachFeature: onEachFeature,
    pointToLayer: function (feature, latlng) {
        return L.marker(latlng, {icon: getIcon(feature.properties.status)});
    }
}).addTo(map);

</script> 
