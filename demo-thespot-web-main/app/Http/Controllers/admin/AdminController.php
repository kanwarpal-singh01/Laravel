<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\LikeUnlike;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Models\PurchasePlan;
use Hash;
use Validator;
use Auth;
use App\User;
use DB;
use Session;
use App\Models\Subscriptions;
use App\Models\UsersDetail;
use App\Models\UserStories;
use Helper;
use App\Models\UserReports;

class AdminController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void

    */

    public function __construct(){

    }

    //Admin dashboard
    public function index(){
         $pagelimit = Helper::get_config('AdminPageLimit');
        $empCount = $totalCount = DB::table('users')->where('role','users')->count();
        $dashboardCount = User::select("*", DB::raw('Count(CASE WHEN status = 1 THEN 1 ELSE NULL END) AS active_user'), DB::raw('Count(CASE WHEN status = 2 THEN 1 ELSE NULL END) AS deactive_user'), DB::raw('Count(CASE WHEN role = "fake_user" THEN 1 ELSE NULL END) AS fake_users'), DB::raw('Count(CASE WHEN status = 0 THEN 1 ELSE NULL END) AS notverified_user'))->first();

        $todayLike = LikeUnlike::whereDate('created_at','=',date('Y-m-d'))->where('type','=','Like')->count();
        $postedStory = UserStories::count();
        $hotspot_users = UsersDetail::count();
          $userStories = DB::table('user_stories')->join('users','users.id','=', 'user_stories.user_id')->groupBy('user_id')->orderBy('user_stories.id', 'DESC')->take(5)->get();
         $reports = UserReports::select('reason','reported.name as reported_name','users.name as reporter_name','user_reports.created_at','reported.status as reported_status','reported.id as reported_id','user_reports.status','user_reports.id as id')->leftjoin('users','user_id','=','users.id')->leftjoin('users as reported','report_user_id','=','reported.id')->orderBy('user_reports.id','DESC')->take(5)->get();
        return view('admin.dashboard', compact('empCount','totalCount', 'dashboardCount', 'todayLike', 'postedStory', 'hotspot_users','userStories','reports','pagelimit'));
    }
    function weekOfMonth($date)
    {
        //Get the first day of the month.
        $firstOfMonth = strtotime(date("Y-m-01", $date));
        //Apply above formula.
        return intval(date("W", $date)) - intval(date("W", $firstOfMonth)) + 1;
    }
    public function chart(Request $request){
        $requestData = $request->all();
        $where = '';
        if($requestData['sub_status'] == '1'){
            $where = ' and DATE(plan_expire) < '.date('Y-m-d');
        }else if($requestData['sub_status'] == '2') {
            $where = ' and DATE(plan_expire) >= '.date('Y-m-d');
        }
        try {
            $subscriptions = Subscriptions::where(['status'=>1]);
            $users = User::where(['status'=>1]);
            if ($requestData['sub_type'] != ''){
                $subscriptions = $subscriptions->where(['id'=> $requestData['sub_type']]);
            }
            $subscriptions = $subscriptions->pluck('plan_name','id')->toArray();
            $case = '';
            foreach ($subscriptions as $key => $value) {
                $case .= (!empty($case))?',':'';
                $case.= 'COUNT(case when (subscription_id) = ' . $key . ' then 1 end) as '.str_replace(' ','_', $value);
            }
            switch ($requestData['type']) {
                case "Daily": {
                    $count = DB::select('select '. $case.' from purchase_plans where DATE(created_at) = "'. date('Y-m-d').'"'.$where);
                    $data = json_decode(json_encode($count[0]), true);
                    $data['label_name'] = date('d-M-Y');
                        krsort($data);
                    $data['Total_Users'] = User::whereDate('created_at',date('d-M-Y'))->count();
                    $charData[] = $data;
                }break;
                case "Weekly": {
                    $startDate = date('Y-m-1');
                    $endDate = date('Y-m-t');
                    $count = $exit =  0;
                    $weekName = ['First', 'Second', 'Third', 'Fourth', 'Fifth'];
                    while ($startDate < $endDate && $count <= 7) {
                        if(strtotime($startDate) > time()){
                            break;
                        }
                        $weekEnd = date('Y-m-d',strtotime("+6 day",strtotime($startDate)));
                        if($weekEnd > date('Y-m-d')){
                            $weekEnd = date('Y-m-d');
                            $exit = 1000;
                        }
                        $ChartData = DB::select('select ' . $case . ' from purchase_plans where DATE(created_at) >= "' . $startDate . '" and DATE(created_at) <= "'. $weekEnd.'"');
                        $data = json_decode(json_encode($ChartData[0]), true);
                        $data['label_name'] = $weekName[$count];
                        krsort($data);
                        $data['Total_Users'] = User::whereDate('created_at','>=', $startDate)->whereDate('created_at', '<=', $weekEnd)->count();
                        $charData[] = $data;
                        $startDate = date('Y-m-d',strtotime('+1 days',strtotime($weekEnd)));
                        $count++;
                    }
                }break;
                case "Monthly": {
                    $monthData = [];
                    for ($month = 1; $month <= date('m'); $month++) {
                        $count = DB::select('select DATE_FORMAT(created_at,"%d-%M-%Y") as label_name,' . $case . ' from purchase_plans where Month(created_at) = "' . $month . '"');
                        $data = json_decode(json_encode($count[0]),true);
                        $data['label_name'] = date('M',strtotime($month));
                        $data['Total_Users'] = User::whereMonth('created_at', $month)->count();
                        $charData[] = $data;
                    }
                }break;
                case "Yearly": {
                    $year = PurchasePlan::orderBy('created_at','asc')->pluck('created_at')->first();
                    $year = date('Y',strtotime($year));
                    for($year = $year; $year <= date('Y'); $year++){
                        $count = DB::select('select ' . $case . ' from purchase_plans where Year(created_at) = "' . $year . '" '. $where);
                            $data = json_decode(json_encode($count[0]), true);
                            $data['label_name'] = (string)$year;
                            krsort($data);
                            $data['Total_Users'] = User::whereYear('created_at', $year)->count();
                            $charData[] = $data;
                    }
                }break;
                default:{

                }break;
            }

            //pr($charData);die;
            if(!empty($charData)){
                //$labelsData = array_column($charData,'label_name');
                //sendResponse('chart Data',1, $charData, ['labels'=> $labelsData]);
                //pr($labelsData);die;
                $labelData = array_keys($charData[0]);
                sendResponse('chart Data',1, $charData, ['labels'=> $labelData]);
            }else{
                sendResponse('No data found');
            }
        } catch (\Throwable $th) {
            sendResponse('Error:'.$th->getMessage());
        }
    }
    public function setChartData(Request $request){
        $requestData = $request->all();
        try {

        if($requestData['type'] == 'Daily'){
            $count = DB::table('users');
            //$count = DB::table('users')->where('id', 40);
            if ($requestData['subscription_type'] != '') {
                $count = $count->select(DB::raw('COUNT(purchase_plans.id) AS count'))->Join('purchase_plans', 'user_id', 'users.id')->where(['subscription_id' => $requestData['subscription_type']])->whereDate('purchase_plans.created_at', date('Y-m-d'))->groupBy('purchase_plans.user_id');
            }else{
                $count = $count->whereDate('users.created_at', date('Y-m-d'));
            }
            $count = $count->count();
            $labelList[] = ['label_name' => date('Y-m-d'), 'count' => $count];
        }else if ($requestData['type'] == 'Weekly') {
                $startDate = date('Y-m-1');
                $startDate = date('Y-m-t');
                $count = 1;
            while($startDate < $startDate && $count < 3){
                pr($count);
                $count++;
            }
            $firstWeek = date('W',strtotime(date('Y-m-1')));
            //Apply above formula.
            $lastWeek = date('W',strtotime(date('Y-m-t')));
            $label_list = DB::table('users');
            if ($requestData['subscription_type'] != '') {
                    $label_list = $label_list->select(DB::raw('WEEK(purchase_plans.created_at) as label_name,COUNT(purchase_plans.user_id) AS count'))->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']])->whereYear('purchase_plans.created_at', date('Y'))->groupBy('purchase_plans.user_id');
            }else{
                $label_list = $label_list->select(DB::raw('WEEK(created_at) as label_name,COUNT(users.id) AS count'))->whereYear('created_at', date('Y'));;
            }
            $label_list = $label_list->groupBy('label_name')->get()->toArray();
            //$label_list = $label_list->groupBy('label_name')->toSql();
            //pr($label_list);die;
            $weekNo = 0;
            $weekName = ['First','Second','Third','Fourth','Fifth'];
            for($i = $firstWeek;$i<= $lastWeek;$i++){
                $count = array_search($i,array_column($label_list, 'label_name'));
                if($count !== false){
                    $count = $label_list[$count]->count;
                }else{
                    $count = 0;
                }
                $labelList[] = ['label_name'=> $weekName[$weekNo].' Week' ,'count'=>$count];
                $weekNo ++;
            }
        }else if($requestData['type'] == 'Monthly'){
            $monthlyData = DB::table('users');
            if ($requestData['subscription_type'] != '') {
                $monthlyData = $monthlyData->select(DB::raw('MONTHNAME(purchase_plans.created_at) as month_name, MONTH(purchase_plans.created_at) as month_no,COUNT(purchase_plans.user_id) AS TOTALCOUNT'))->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']])->whereYear('purchase_plans.created_at', date('Y'))->groupBy('purchase_plans.user_id');
            } else {
                $monthlyData = $monthlyData->select(DB::raw('MONTHNAME(created_at) as month_name, MONTH(created_at) as month_no,COUNT(users.id) AS TOTALCOUNT'))->whereYear('created_at', date('Y'));;
            }



            /* if ($requestData['subscription_type'] != '') {
                $monthlyData = $monthlyData->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']]);
            } */
            $monthlyData =  $monthlyData->groupBy('month_no')->get()->toArray();
            $monthlyData = json_decode(json_encode($monthlyData), true);
            $month = '';
            $labelList= [];
            for ($month = 1; $month <= date('m'); $month++) {
                $keys = array_search($month, array_column($monthlyData, 'month_no'));
                $monthName =  $month_name = date("F", mktime(0, 0, 0, $month, 10));
                if ($keys !== false) {
                    $count = $monthlyData[$keys]['TOTALCOUNT'];
                } else {
                    $count = 0;
                }

                $labelList[$month] = ['label_name' => $monthName, 'count' => $count];
            }
        }else if ($requestData['type'] == 'Quarterly') {

        }else if ($requestData['type'] == 'Yearly') {
            $labelList = DB::table('users')
            ->select(DB::raw('YEAR(users.created_at) as label_name,COUNT(users.id) AS count'));
                if ($requestData['subscription_type'] != '') {
                    $labelList = $labelList->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']]);
                }
            $labelList = $labelList->groupBy('label_name')->get()->toArray();
            //$labelList[] = ['label_name' => date('Y-m-d'), 'count' => $count];
        }

        if(!empty($labelList)){
            sendResponse('chart list',1, $labelList);
        }else{
            sendResponse('No data found');
        }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function setSubscriptionData(Request $request){
        $requestData = $request->all();
        try {
            $count = PurchasePlan::with('plan');
            if(isset($requestData['subscription_type'])){
                $count = $count->where(['subscription_id', $requestData['subscription_type']]);

            }
            if(isset($requestData['subscription_status'])){
                if($requestData['subscription_status'] == '0'){
                    $count = $count->whereDate(['plan_expire','<',date('Y-m-d')]);
                }else if($requestData['subscription_status'] == '1'){
                    $count = $count->whereDate(['plan_expire','>',date('Y-m-d')]);
                }
            }
            switch($requestData['type']){
                case "Daily":{
                    $count = $count->whereDate('created_at',date('Y-m-d'))->count();
                }break;
                case "Weekly": {
                    $count = $count->select(DB::raw('WEEK(created_at) as label_name,COUNT(users.id) AS count'))->whereYear('created_at', date('Y'));
                }break;
                case "Monthly": {
                    $count = $count->select(DB::raw('MONTHNAME(created_at) as month_name, MONTH(created_at) as month_no,COUNT(users.id) AS TOTALCOUNT'))->whereYear('created_at', date('Y'));
                }break;
                case "Yearly": {
                    $count = $count->select(DB::raw('YEAR(users.created_at) as label_name,COUNT(users.id) AS count'));

                }break;
            }
            $count = $count->get()->toArray();
            pr($count);die;
            /* if ($requestData['type'] == 'Weekly') {
                $dowMap = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
                $firstWeek = date('W', strtotime(date('Y-m-1')));
                //Apply above formula.
                $lastWeek = date('W', strtotime(date('Y-m-t')));
                $label_list = DB::table('users');
                if ($requestData['subscription_type'] != '') {
                    $label_list = $label_list->select(DB::raw('WEEK(purchase_plans.created_at) as label_name,COUNT(purchase_plans.user_id) AS count'))->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']])->whereYear('purchase_plans.created_at', date('Y'))->groupBy('purchase_plans.user_id');
                } else {
                    $label_list = $label_list->select(DB::raw('WEEK(created_at) as label_name,COUNT(users.id) AS count'))->whereYear('created_at', date('Y'));;
                }
                $label_list = $label_list->groupBy('label_name')->get()->toArray();
                //$label_list = $label_list->groupBy('label_name')->toSql();
                //pr($label_list);die;
                $weekNo = 0;
                $weekName = ['First', 'Second', 'Third', 'Fourth', 'Fifth'];
                for ($i = $firstWeek; $i <= $lastWeek; $i++) {
                    $count = array_search($i, array_column($label_list, 'label_name'));
                    if ($count !== false) {
                        $count = $label_list[$count]->count;
                    } else {
                        $count = 0;
                    }
                    $labelList[] = ['label_name' => $weekName[$weekNo] . ' Week', 'count' => $count];
                    $weekNo++;
                }
            } else if ($requestData['type'] == 'Monthly') {
                $monthlyData = DB::table('users');
                if ($requestData['subscription_type'] != '') {
                    $monthlyData = $monthlyData->select(DB::raw('MONTHNAME(purchase_plans.created_at) as month_name, MONTH(purchase_plans.created_at) as month_no,COUNT(purchase_plans.user_id) AS TOTALCOUNT'))->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']])->whereYear('purchase_plans.created_at', date('Y'))->groupBy('purchase_plans.user_id');
                } else {
                    $monthlyData = $monthlyData->select(DB::raw('MONTHNAME(created_at) as month_name, MONTH(created_at) as month_no,COUNT(users.id) AS TOTALCOUNT'))->whereYear('created_at', date('Y'));;
                }
                $monthlyData =  $monthlyData->groupBy('month_no')->get()->toArray();
                $monthlyData = json_decode(json_encode($monthlyData), true);
                $month = '';
                $labelList = [];
                for ($month = 1; $month <= date('m'); $month++) {
                    $keys = array_search($month, array_column($monthlyData, 'month_no'));
                    $monthName =  $month_name = date("F", mktime(0, 0, 0, $month, 10));
                    if ($keys !== false) {
                        $count = $monthlyData[$keys]['TOTALCOUNT'];
                    } else {
                        $count = 0;
                    }

                    $labelList[$month] = ['label_name' => $monthName, 'count' => $count];
                }
            } else if ($requestData['type'] == 'Quarterly') {
            } else if ($requestData['type'] == 'Yearly') {
                $labelList = DB::table('users')
                    ->select(DB::raw('YEAR(users.created_at) as label_name,COUNT(users.id) AS count'));
                if ($requestData['subscription_type'] != '') {
                    $labelList = $labelList->Join('purchase_plans', 'user_id', '=', 'users.id')->where(['subscription_id' => $requestData['subscription_type']]);
                }
                $labelList = $labelList->groupBy('label_name')->get()->toArray();
                //$labelList[] = ['label_name' => date('Y-m-d'), 'count' => $count];
            } */

            if (!empty($labelList)) {
                sendResponse('chart list', 1, $labelList);
            } else {
                sendResponse('No data found');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }



    //show form for update admin profile
    public function updateprofile(){
        $userid = Auth::id();
        $user   = User::where('id', $userid)->first();
        return view('admin/profileupdate', ['user' => $user]);

    }
    //show form for update admin profile
    public function updatepassword(){
        $userid = Auth::id();
        $user   = User::where('id', $userid)->first();
        return view('admin/updatepassword', ['user' => $user]);

    }

    //save admin updated profile
    public function updateuser(Request $request){
        $this->validate($request, array(
            'name' => 'required|max:83',
            )
        );
        $userid   = Auth::id();
        $user     = User::where('id', $userid)->first();
        $input = $request->all();
        if ($request->file())
        {
            $file = $request->file('profile_image');
            if ($file){
                if(file_exists(public_path() .'/uploads/user/'.$user->profile_image))
                {
                    if($user->profile_image != 'no_image.jpg'){
                        @unlink(public_path() .'/uploads/user/'.$user->profile_image);
                    }
                }

                $destinationPath = 'public/uploads/user/';
                $extension = $request->file('profile_image')->getClientOriginalExtension();
                $filename = rand(11111, 99999) . '.' . $extension;
                $file->move($destinationPath, $filename);
                $user->profile_image = $filename;
            }
        }

        $user->name = $input['name'];
        $user->save();
        return redirect('/admin/updateprofile')->with('message', 'Profile update successfully ');
    }

    //admin change password
    public function changepassword(Request $request){
        $request->validate([
            'old_password' => ['required'],
            'new_password' => ['required',
            'string',
            'min:6',
            'regex:/[a-z]/',      // must contain at least one lowercase letter
            'regex:/[A-Z]/',      // must contain at least one uppercase letter
            'regex:/[0-9]/',      // must contain at least one digit
            'regex:/[@$!%*#?&]/',],
            'password_confirmation' => ['required','same:new_password'],
            ]);
            $url = $request->segments();
            $id   = Auth::user()->id;
            $user = User::find($id);
            if (Hash::check($request->input('old_password'), $user->password)){
                $user->password = Hash::make($request->input('new_password'));
                $user->save();
                Session::flash('message', 'Password Updated successfully');
                return redirect('admin/updateprofile?par=changepassword');
            }else{
                Session::flash('error', 'Old Password does Not match');
                return redirect('admin/updateprofile?par=changepassword');
            }

        }

    }
