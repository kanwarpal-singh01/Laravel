<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Session;
use Auth;
use DB;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Credit;
use Helper;
use Mail;

class PaymentController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {  
         $credit_fees=Credit::first();
       
        return view('admin.credit.index',compact('credit_fees'));
   
     }

   


      public function history(Request $request){

        $payments=DB::table('payments');

        if($request->from !=null && $request->to !=null){
          $payments=$payments->where('payments.created_at','>=',date('Y-m-d',strtotime($request->from )))->where('payments.created_at','<=',date('Y-m-d',strtotime($request->to )) );
        }

        $payments=$payments->join('users','payments.user_id','users.id')->select('payments.*','users.user_name','users.role')->get();
     
        return view('admin.credit.history',compact('payments'));

      }

  
}



