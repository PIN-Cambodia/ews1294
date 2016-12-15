<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Khill\Lavacharts\Laravel\LavachartsFacade as Lava;

class sensorLogChartCtrl extends Controller
{
    public function createChart()
    {
        $sensenlogTable = \Lava::DataTable();
        $sensenlogTable->addDateColumn('Day of Month')
                        ->addNumberColumn('Alarm')
                        ->addNumberColumn('Flood')
                        ->addNumberColumn('2016');
        for($a=1;$a<30;$a++)
        {
            $sensenlogTable->addRow(['2016-10-' . $a, 680,655, rand(200,800)]);
        }
        $chart = Lava::LineChart('SensorLogChart',$sensenlogTable);

        return view('sensorLogChart');
    }


}
