<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App;
use DB;
use App\Models\province;
use Illuminate\Support\Facades\Input;

class CallLogReportController extends Controller
{
    // CallLog Report View
    public function CallLogReportView()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
        {
            $all_province = province::all();
        }
        if(Auth::user()->hasRole('PCDM'))
        {
            $pcdm_data=DB::table('role_user')->where('user_id', Auth::user()->id)->first();
            $all_province=DB::table('province')->where('PROCODE', $pcdm_data->province_code)->get();
        }
        return view('report/calllogreport', ['allprovince'=> $all_province]);
    }

    // function to select call report of selected province
    public function getCallLogReport(Request $request)
    {
        // select activities data in a whole province based on activity created_date
        if($request->prov_id <=9)
            $activity_data_query = DB::select(DB::raw("SELECT * FROM activities WHERE list_commune_codes like '$request->prov_id%' GROUP BY activity_id HAVING LENGTH(SUBSTRING_INDEX(`list_commune_codes`,',',1)) < 6 "));
        else
            $activity_data_query = DB::select(DB::raw("SELECT * FROM activities WHERE list_commune_codes like '$request->prov_id%' GROUP BY activity_id HAVING LENGTH(SUBSTRING_INDEX(`list_commune_codes`,',',1)) >= 6 "));
        $call_logs_result = $this->getCallLogBody($activity_data_query, false);
        return $call_logs_result;
    }

    // function to select call report of selected province
    public function getCallLogReportPerActivity()
    {
        $activity_id = Input::get('activID');
        // select activities data in a whole province based on activity created_date
        $activity_record = DB::table('activities')->where('activity_id', $activity_id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        $result = $this->getCallLogBody($activity_record, true);
        return view('report/calllogreport_per_activity', ['result'=> $result]);

    }

    // function to select call_logs report to be displayed
    public function getCallLogBody($activity_data, $single_array)
    {
       // dd($activity_data);
        $i=0;
        $all_arr = array(); $arr_each="";
        // loop each activity record
        if(!empty($activity_data))
        {
            foreach ($activity_data as $activity)
            {
                $commune_name_all="";$success_call=0; $failed_call=0;
                $busy_call=0; $no_answer_call=0; $current_total_call=0;
                $i=$i+1;
                // to display each commune name per activity record
                $each_commune_code = explode(',',$activity->list_commune_codes);
                foreach($each_commune_code as $commune_code)
                {
                    $commune_name = DB::table('commune')->where('CCode', $commune_code)->get();
                    if(!empty($commune_name))
                    {
                        if (App::getLocale()=='km')
                            $commune_name_all .= $commune_name[0]->CName_kh . ", ";
                        else
                            $commune_name_all .= $commune_name[0]->CName_en . ", ";
                    }
                }
                // get all call logs data per activity id
                $call_log_each_activity = DB::table('calllogs')
                    ->select('*', DB::raw('count("result") as total_result'))
                    ->where('activity_id', $activity->activity_id)
                    ->groupBy('activity_id', 'result')
                    ->get();
                if(!empty($call_log_each_activity))
                {
                    foreach($call_log_each_activity as $calllog_activity)
                    {
                        /**
                         * calllogs.result = 1:Completed; 2:Failed; 3:Busy; 4:No Answer
                         */
                        if($calllog_activity->result == '1')
                            $success_call = $calllog_activity->total_result;
                        elseif($calllog_activity->result == '2')
                            $failed_call = $calllog_activity->total_result;
                        else if($calllog_activity->result == '3')
                            $busy_call = $calllog_activity->total_result;
                        elseif($calllog_activity->result == '4')
                            $no_answer_call = $calllog_activity->total_result;
                    }
                    $current_total_call = $success_call + $failed_call + $busy_call + $no_answer_call;
                }
                // each array of a record
                $arr_each= array("No" => $i,
                    "created_at"=>$activity->created_at,
                    'sound_file'=>$activity->sound_file,
                    'commune_name_all'=>$commune_name_all,
                    'no_of_phones_called'=>$activity->no_of_phones_called,
                    'success_call'=>$success_call,
                    'failed_call'=>$failed_call,
                    'busy_call'=>$busy_call,
                    'no_answer_call'=>$no_answer_call,
                    'current_total_call'=>$current_total_call);
                // push each array of a record into all_array
                array_push($all_arr, $arr_each);
            } // ./foreach($activity_data)
        } // . if(!empty($activity_data))
        if ($single_array==true) $return_result = $arr_each;
        else $return_result = array("data" => $all_arr);
        return $return_result;
        //return array("data" => $all_arr);
    }

}
