<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;

class CallLogReportController extends Controller
{
    // CallLog Report View
    public function CallLogReportView()
    {
        if(Auth::user()->hasRole('admin') || Auth::user()->hasRole('NCDM'))
        {
//            $all_users = User::all();
        }
        if(Auth::user()->hasRole('PCDM'))
        {
//            $all_users = User::where('name', '!=', 'admin')
//                -> Where('is_delete', '!=', '1')
//                -> get();
        }
       // return view('report/calllogreport',['userlists' => $all_users]);
    }

    // function to select call report of selected province
    public function getCallLogReport(Request $request)
    {
        echo "get callLog Report";
    }
}
