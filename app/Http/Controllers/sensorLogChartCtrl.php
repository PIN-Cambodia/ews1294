<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Khill\Lavacharts\Configs\HorizontalAxis;
use Khill\Lavacharts\Configs\VerticalAxis;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class sensorLogChartCtrl extends Controller
{
    public function createChart30Days()
    {
        $sensenlogTable = \Lava::DataTable();
        $sensenlogTable
                ->addStringColumn('')
                ->addNumberColumn('Water Level')
                ->addNumberColumn('Emergency Level')
                ->addNumberColumn('Warning Level');


        $sensor_id = Input::get('sensor_id');
        $graph_type = Input::get('type');
        if($graph_type==2)
        {
            $sensorlogs = DB::table('sensorlogs')
                ->select(DB::raw("id, date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)
                ->groupBy('time')
                ->orderBy('timestamp')->limit(30)->get();
        }
        else
        {
            $sensorlogs = DB::table('sensorlogs')
                ->select(DB::raw("id, timestamp as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)->orderBy('timestamp')->limit(24)->get();
        }


        $sensortrigger = DB::table('sensortriggers')
            ->select(DB::raw("level_warning, level_emergency"))
            ->where('sensor_id','=',$sensor_id)
            ->first();
//        dd($sensorlogs);

        if(!empty($sensortrigger))
        {
            foreach($sensorlogs as $v => $sensorlog)
            {
                $sensenlogTable->addRow([$sensorlog->time , $sensorlog->stream_height, $sensortrigger->level_emergency, $sensortrigger->level_warning]);
            }
            $chart = Lava::LineChart('SensorLogChart',$sensenlogTable)
                ->setOptions(['pointSize' => 1,
                    'curveType' => 'function',
                    'height' => 350

                ]);

            return view('sensorLogChart',['sensorId'=>$sensor_id, 'graph_type'=>$graph_type]);
        }
        else
        {

            return '<p align="center" style="margin-top: 200;">Error: Sensor ID "' . $sensor_id . '" is not found in trigger management.<br><br><br><a href="/sensortrigger">Click here</a> to add new trigger for this sensor.</p>';
        }


    }
}
