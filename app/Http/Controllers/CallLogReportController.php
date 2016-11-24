<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App;
use DB;
use App\Models\province;

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
        $activity_data = DB::table('activities')->where('list_commune_codes', 'like', '%' . $request->prov_id . '%')
                        ->get();
        $i=0; $return_data_str="";
        // loop each activity record
        foreach ($activity_data as $activity)
        {
            $commune_name_all="";$success_call=0; $failed_call=0; $busy_call=0; $no_answer_call=0;
            $i=$i+1;
            // to display each commune name per activity record
            $each_commune_code = explode(',',$activity->list_commune_codes);
            foreach($each_commune_code as $commune_code)
            {
                $commune_name = DB::table('commune')->where('CCode', $commune_code)->get();
                if (App::getLocale()=='km')
                    $commune_name_all .= $commune_name[0]->CName_kh . ", ";
                else
                    $commune_name_all = $commune_name[0]->CName_en;
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
            }

            // display data
            if($i%2==0) $tr= "<tr class='active'>";
            else $tr="<tr>";
            $return_data_str .= $tr . "<td class='text-center'>" . $i. "</td>"
                                    //. "<td>" . $activity->activity_id . "</td>"
                                    . "<td class='text-center'>" . $activity->created_at . "</td>"
                                    . "<td>" . $activity->sound_file . "</td>"
                                    . "<td>" . $commune_name_all . "</td>"
                                    . "<td class='text-center'>" . $activity->no_of_phones_called . "</td>"
                                    . "<td class='text-center'>" . $success_call . "</td>"
                                    . "<td class='text-center'>" . $failed_call . "</td>"
                                    . "<td class='text-center'>" . $busy_call . "</td>"
                                    . "<td class='text-center'>" . $no_answer_call . "</td>"
                                . "</tr>";
        }
        // display data
        $return_data = "<table class='table table-bordered'>"
                            . "<thead>"
                                . "<tr>"
                                    . " <th rowspan='2' class='text-center active'> " . trans('pages.tbl_title_number') . " </th> "
                                    //. "<th rowspan='2' class='text-center active'> Activity_id </th> "
                                    . "<th rowspan='2' class='text-center active'> " . trans('pages.tbl_title_date') . " </th> "
                                    . "<th rowspan='2' class='text-center active'> " . trans('pages.tbl_title_sound_file') . " </th> "
                                    . "<th rowspan='2' class='text-center active'> " . trans('pages.tbl_title_list_of_communes') . " </th> "
                                    . "<th rowspan='2' class='text-center active'> " . trans('pages.tbl_no_of_phone_called') . " </th> "
                                    . "<th colspan='4' class='text-center active'> " . trans('pages.tbl_title_call_status') . " </th> "
                                . "</tr>"
                                . "<tr>"
                                    . "<th class='text-center active'> " . trans('pages.tbl_title_completed') . " </th> "
                                    . "<th class='text-center active'> " . trans('pages.tbl_title_failed') . " </th> "
                                    . "<th class='text-center active'> " . trans('pages.tbl_title_busy') . " </th> "
                                    . "<th class='text-center active'> " . trans('pages.tbl_title_no_answer') . " </th> "
                                . "</tr>"
                            . "</thead>"
                            . "<tbody>"
                                . $return_data_str
                            . "</tbody>"
                        . "</table>";

        return $return_data;
    }
}
