<!DOCTYPE html>
<html style="height: 100%">
   <head>
       <meta charset="utf-8">
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
var yminVal = 0

//populate the data and date arrays with records from JSON response file.
 @foreach($sensorlogs as $sensorlog) 
   data.push(parseInt("{{$sensorlog->stream_height}}"))
   date.push("{{$sensorlog->timestamp}}")
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
//TO adjust the y axis minimum and maxium extents...
//ADD 10% of delta Y to each value, to padd the graph
//for visual effect.
//ROUND the Y axis to the nearst value tenth.
ymaxVal = Math.max(...data);
yminVal = Math.min(...data);
var total = Math.abs(ymaxVal - yminVal);
total = (total * 0.1);
function precisionRound(number, precision) {
  var factor = Math.pow(10, precision);
  return Math.round(number * factor) / factor;
}
ymaxVal = (ymaxVal + total);
yminVal =(yminVal- total);
ymaxVal = precisionRound(ymaxVal, -1);
yminVal = precisionRound(yminVal, -1);

//ensure alert levels are within the Y axis range.
//as these are dynamic
if (yminVal > warningThreshold ) {
    yminVal = (parseInt(warningThreshold) - 50);
  }

if (ymaxVal < alertThreshold ) {
    ymaxVal = (parseInt(alertThreshold) + 50);
  }  

//reshuffle. echart needs data in correct order..
data.reverse();
date.reverse();
var dateRangeStart = date[0];
var dateRangeEnd = date[date.length - 1];
var graphTitle = "Last 6 hours - " + sensor_description;

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
        textStyle: {fontSize: 15}
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
                  var value = new Date(value);
                  var h = value.getHours();
                  var m = value.getMinutes();
                  var s = value.getSeconds();
                  // add a zero in front of numbers<10
                  if (m < 10) {
                    m = "0" + m;
                  }

                  var sendback = h + ":" + m;
                  return sendback; 
                }

}},
    yAxis: {
        type: 'value',
        //boundaryGap: ['10%', '10%'],
        max: ymaxVal,
        min: yminVal
    },
   // dataZoom: [{
   //     type: 'inside',
   //     start: 0,
   //     end: 100 //this is percentage of total data retrieval to show as zooomed in/out.
   // },   {
   //     start: 0,
   //     end: 30,
   //     handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
   //     handleSize: '100%',
   //     handleStyle: {
   //         color: '#fff',
   //         shadowBlur: 3,
    //        shadowColor: 'rgba(0, 0, 0, 0.6)',
    //        shadowOffsetX: 2,
    //        shadowOffsetY: 2
    //    }
    //}],
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
            xAxis: dateRangeStart, // we have to defined line attributes only here (not in the end point)
            yAxis: warningThreshold,
            lineStyle: {
              normal: {
                color: "#ffb200"
              }
            },
            label: {
              normal: {
                show: true,
                position: 'end',
                formatter: 'Warning'
              }
            }
          },
          // end point of the line
          {
            xAxis: dateRangeEnd,
            yAxis: warningThreshold,
            
                  }   
                ],
        
        [                 
           { // start point of the Alert line
            xAxis: dateRangeStart, // we have to defined line attributes only here (not in the end point)
            yAxis: alertThreshold,
            lineStyle: {
              normal: {
                color: "#ff2323"
              }
            },
            label: {
              normal: {
                show: true,
                position: 'end',
                formatter: 'Alert'
              }
            }
          },
          // end point of the alert line
          {
            xAxis: dateRangeEnd,
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