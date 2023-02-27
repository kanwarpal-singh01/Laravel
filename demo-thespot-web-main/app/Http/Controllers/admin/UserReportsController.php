<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserReports;
use Illuminate\Http\Request;
use Session;
use Helper;

class UserReportsController extends Controller
{
    public function index(Request $request){
        if($request->isMethod('Get')){
            $requestData = $request->all();
            $query_name = '';
                $pagelimit = Helper::get_config('AdminPageLimit');
            if(isset($requestData['name'])){
                $query_name = $requestData['name'];
                $reports = UserReports::select('reason', 'reporter.name as reported_name', 'reporter.name as report_to', 'users.name as report_by', 'users.name as reporter_name','reporter.status as reported_status','user_reports.created_at','user_reports.id as id','user_reports.status','reporter.id as reported_id')->leftjoin('users','user_id','=','users.id')->leftjoin('users as reporter','report_user_id','=','reporter.id')
                ->where('reporter.name','like','%'.$requestData['name'].'%')->orWhere('users.name','like','%'.$requestData['name'].'%')->orderBy('user_reports.id','DESC')
                ->paginate($pagelimit);
            }else{
                $reports = UserReports::select('reason','reported.name as reported_name','users.name as reporter_name','user_reports.created_at','reported.status as reported_status','reported.id as reported_id','user_reports.status','user_reports.id as id')->leftjoin('users','user_id','=','users.id')->leftjoin('users as reported','report_user_id','=','reported.id')->orderBy('user_reports.id','DESC')->paginate($pagelimit);
            }
            //pr($reports);die;
        }
        return view('admin.userReports.index',compact('reports','query_name', 'pagelimit'))->render();
    }

    public function changeStatus(Request $request,$id,$status){
        $id = base64_decode($id);
        UserReports::where(['id'=>$id])->update(['status'=>$status]);
        Session::flash('message', 'Report status has been changed');
        return redirect()->route('reportUserList');
    }
}
