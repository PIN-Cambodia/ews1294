<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class sensorLogChartCtrl extends Controller
{
    /**
     * Function to retrieve data of sensor and create Chart.
     * @return Chart to sensorLogChart view with sensor id and graph type (1=first 24 readings; 2= first reading within 30 days)
     */
    public function createChart()
    {
        $sensenlogTable = \Lava::DataTable();
        // configure Chart columns
        $sensenlogTable
                ->addStringColumn('')
                ->addNumberColumn('Water Level')
                ->addNumberColumn('Emergency Level')
                ->addNumberColumn('Warning Level');
          

        // test commands
        $sensor_id = Input::get('sensor_id');
        $graph_type = Input::get('type');
        if($graph_type==2)
        {
            // retrieve first reading within last 30 days for $sensor_id
            $sensorlogs = DB::table('sensorlogs')
                ->select(DB::raw("id, date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)
                ->groupBy('time')
                ->orderBy('id','desc')
                ->limit(30)->get();
        }
        else
        {
            // retrieve first 24 readings for $sensor_id
            $sensorlogs = DB::table('sensorlogs')
                ->select (DB::raw("id,date_format(timestamp,'%H:%i') as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)
                ->orderBy('timestamp','desc')               
                ->limit(24)->get();

        }
        // select sensortrigger info from database
        $sensortrigger = DB::table('sensortriggers')
            ->select(DB::raw("level_warning ,level_emergency"))
            ->where('sensor_id','=',$sensor_id)
            ->first();


        // if(!empty($sensortrigger))
        // {
        //     // add row data into datatable for Chart
        //     foreach($sensorlogs as $v => $sensorlog)
        //     {
        //         $sensenlogTable->addRow([$sensorlog->time, $sensorlog->stream_height, $sensortrigger->level_emergency, $sensortrigger->level_warning]);
        //     }
            
   if(!empty($sensortrigger))
        {
            // add row data into datatable for Chart
            if($graph_type=1) {
                for ($i = count($sensorlogs) - 1; $i >= 0; $i--) {
                    $sensorlog = $sensorlogs[$i];
                     $sensenlogTable->addRow([$sensorlog->time, $sensorlog->stream_height, $sensortrigger->level_emergency, $sensortrigger->level_warning]);
                }
            }else{
               for ($i = count($sensorlogs) - 1; $i >= 0; $i--) {
                    $sensorlog = $sensorlogs[$i];
                     $sensenlogTable->addRow([$sensorlog->time, $sensorlog->stream_height, $sensortrigger->level_emergency, $sensortrigger->level_warning]);
                }
        }

     
            // generate Chart as a LineChart
            Lava::LineChart('SensorLogChart',$sensenlogTable)
                ->setOptions(['pointSize' => 1,
                    'curveType' => 'function',
                    'height' => 350,
                    'vAxis' =>['title' => 'Level of water in " cm " ']

                ]);
                

            return view('sensorLogChart',['sensorId'=>$sensor_id, 'graph_type'=>$graph_type]);
        }
        else
        {
            // if no trigger info of $sensor_id, display error message.
            return '<p align="center" style="margin-top: 200;">' . trans('sensors.sensorChartErrorID'). $sensor_id . trans('sensors.sensorChartErrorID').
            '<br><br><br><a href="/sensortrigger">' . trans('sensors.sensorChartErrorClickHere').'</a>'. trans('sensors.sensorChartErrorToAdd').'</p>';
        }
    }
   
}
