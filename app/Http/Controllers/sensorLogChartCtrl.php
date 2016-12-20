<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class sensorLogChartCtrl extends Controller
{
    public function createChart30Days()
    {
        $sensenlogTable = \Lava::DataTable();
        $sensenlogTable->addDateColumn('Day of Month')
                        ->addNumberColumn('Warning')
                        ->addNumberColumn('Emergency')
                        ->addNumberColumn('Stream Height');

        $sensor_id = Input::get('sensor_id');
        $graph_type = Input::get('type');
        if($graph_type==2)
        {
            $sensorlogs = DB::table('sensorlogs')
                ->select(DB::raw("date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)
                ->groupBy('time')
                ->orderBy('timestamp','desc')->limit(30)->get();
        }
        else
        {
            $sensorlogs = DB::table('sensorlogs')
                ->select(DB::raw("date_format(date(date_sub(timestamp,interval 0 hour)),GET_FORMAT(DATE,'ISO')) as time, stream_height"))
                ->where('sensor_id','=',$sensor_id)->orderBy('timestamp','desc')->limit(24)->get();
        }


        $sensortrigger = DB::table('sensortriggers')
            ->select(DB::raw("level_warning, level_emergency"))
            ->where('sensor_id','=',$sensor_id)
            ->first();
        //dd($sensorlogs);

        foreach($sensorlogs as $sensorlog)
        {
            $sensenlogTable->addRow([$sensorlog->time, $sensortrigger->level_warning, $sensortrigger->level_emergency, $sensorlog->stream_height]);
        }
        $chart = Lava::LineChart('SensorLogChart',$sensenlogTable);

        return view('sensorLogChart',['sensorId'=>$sensor_id, 'graph_type'=>$graph_type]);
    }
}