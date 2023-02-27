<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\User;
use App\Models\EmailTemplate;
use App\Models\Credit;
use Helper;
use Mail;

class ReportController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function user_report(Request $request){

      $users=DB::table('users')->where('role','!=','sub-recruiter')->where('role','!=','admin');

      if($request->from !=null && $request->to !=null){
        $users=$users->where('users.created_at','>=',date('Y-m-d',strtotime($request->from )))->where('users.created_at','<=',date('Y-m-d',strtotime($request->to )) );
      }

      $users=$users->orderBy('id','desc')->get();


      return view('admin.report.users_report',compact('users'));

    }



      public function payment_report(Request $request){

        $payments=DB::table('payments');

       
      if($request->from !=null && $request->to !=null){
          $payments=$payments->where('payments.status',1)->where('payments.created_at','>=',date('Y-m-d',strtotime($request->from )))->where('payments.created_at','<=',date('Y-m-d',strtotime($request->to )) );
        }

        $payments=$payments->join('users','payments.user_id','users.id')

        
        ->select('payments.*','users.user_name','users.role')->orderBy('payments.id','desc')->get();

      
     
        return view('admin.report.payment_report',compact('payments'));

      }

        public function change_status(Request $request, $payment_id){



         $status= DB::table('payments')->where('id',$payment_id)->pluck('status')->first();
         if($status){

          DB::table('payments')->where('id',$payment_id)->update(['status'=>0]);
          return redirect()->back();
         }
         else{
          DB::table('payments')->where('id',$payment_id)->update(['status'=>1]);
          return redirect()->back();
         }
          


        }

  
}



