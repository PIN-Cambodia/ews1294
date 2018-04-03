<!DOCTYPE html>
<html style="height: 100%">
   <head>
   
   </head>
   <body style="height: 100%;width: 100%; margin: 0">
       <div id="container" style="height: 100%;width: 100%" ></div>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/echarts.min.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-gl/echarts-gl.min.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts-stat/ecStat.min.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/dataTool.min.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/china.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/map/js/world.js"></script>
       <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=ZUONbpqGBsYGXNIYHicvbAbM"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/echarts/extension/bmap.min.js"></script>
       <script type="text/javascript" src="http://echarts.baidu.com/gallery/vendors/simplex.js"></script>
       <script type="text/javascript">
       
var dom = document.getElementById("container");
var myChart = echarts.init(dom);
var app = {};
option = null;

var date = []
var data = []

//Set two variables for min-max date ranges - used to set length of Marker lines (Alert/Warning levels)
var sensor_description = ""
var alertThreshold = ""
var warningThreshold = ""
var ymaxVal = 0

//populate the data and date arrays with records from JSON response file.
 @foreach($sensorlogs as $sensorlog) 
   data.push(parseInt("{{$sensorlog->stream_height}}"))
   var tempdate = "{{$sensorlog->timestamp}}"
   //replace spaces so iOS recognises date format on mobile devices.
   date.push(new Date(tempdate.replace(' ', 'T')))
 @endforeach

//pull sensor name
 @foreach($sensors as $sensor)
   sensor_description = "{{$sensor->additional_location_info}}";
 @endforeach

 //pull sensor trigger levels
@foreach($triggers as $trigger_level)
   alertThreshold =  "{{$trigger_level->level_emergency}}";
   warningThreshold = "{{$trigger_level->level_warning}}";
 @endforeach

//-- Neaten the graph --
//TO adjust the y axis maxium extents...
//ADD 10% of delta Y to each value, to padd the graph
//for visual effect.
//ROUND the Y axis to the nearst value tenth.
ymaxVal = Math.max(...data);
function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}
ymaxVal = (ymaxVal * 1.1);
ymaxVal = precisionRound(ymaxVal, -1);

//ensure alert levels are within the Y axis range.
//as these are dynamic
if (ymaxVal < alertThreshold ) {
    ymaxVal = (parseInt(alertThreshold) * 1.1);
  }  

//reshuffle. echart needs data in correct order..
data.reverse();
date.reverse();
var recordCount = date.length - 1;
var graphTitle = sensor_description;

option = {
    tooltip: {
        trigger: 'axis',
        position: function (pt) {
            return [pt[0], '10%'];
        }
    },
    title: {
        left: 'center',
        text: graphTitle,
        textStyle: {fontSize: 13}
    },
    toolbox: {
        feature: {
            dataZoom: {
                show : false,
                yAxisIndex: 'none'
            },
            restore: {
             show : false},
            saveAsImage: {
              show: false,
             title : 'Save Image'}
        }
    },
    xAxis: {
        type: 'category',
        boundaryGap: false,
        data: date,
        axisLabel: {  //FORMAT the lables to show just hours.           
                show: true,
                formatter: function startTime(value) {
                  var valuex = new Date(value);
                  var h = valuex.getHours();
                  var m = valuex.getMinutes();
                  var s = valuex.getSeconds();
                  // add a zero in front of numbers<10
                  var newM = "";
                  if (parseInt(m) < 10) {
                    newM = "0" + String(m);}
                  else 
                    newM = String(m);
                  
                  var sendback = "";
                  sendback = String(h) + ":" + String(newM) ;
                  return sendback; 
                }

}},
    yAxis: {
        type: 'value',
        boundaryGap: ['0%', '5%'],
        max: ymaxVal,
        min: 0,
        axisLabel: {
          showMaxLabel: false,
          inside: false,
          rotate: 45,
        }
        
    },
    series: [
        {
            name:'Water Level',
            type:'line',
            smooth:true,
            symbol: 'none',
            sampling: 'average',
            itemStyle: {
                normal: {
                    color: 'rgb(240,248,255)'
                }
            },
            areaStyle: {
                normal: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                        offset: 0,
                        color: 'rgb(0, 191, 255)'
                    }, {
                        offset: 1,
                        color: 'rgb(0, 0, 128)'
                    }])
                }
            },
            data: data,
            
            markLine: {
            name:'Warning',
            type:'line',
            smooth:true,
            data: [[                 
           { // start point of the line
            xAxis: 0, // we have to defined line attributes only here (not in the end point)
            yAxis: warningThreshold,
            lineStyle: {
              normal: {
                color: "#ffb200"
              }
            },
            label: {
              normal: {
                show: false,
                position: 'end',
                formatter: 'Warning'
              }
            }
          },
          // end point of the line
          {
            xAxis: recordCount,
            yAxis: warningThreshold,
            
                  }   
                ],
        
        [                 
           { // start point of the Alert line
            xAxis: 0, // we have to defined line attributes only here (not in the end point)
            yAxis: alertThreshold,
            lineStyle: {
              normal: {
                color: "#ff2323"
              }
            },
            label: {
              normal: {
                show: false,
                position: 'end',
                formatter: 'Alert'
              }
            }
          },
          // end point of the alert line
          {
            xAxis: recordCount,
            yAxis: alertThreshold,
            
                  }   
                ]   
              ]
            }  
        }         
    ]
};
;
if (option && typeof option === "object") {
    myChart.setOption(option, true);
}
       </script>
   </body>
</html>