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

class CreditController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {  
         $credit_fees=Credit::first();
       
        return view('admin.credit.index',compact('credit_fees'));
   
     }

     public function update(Request $request)
     {  
        
          try{
            Credit::where('id',1)->update($request->except('_token'));
            return redirect()->back()->with('message','credit updated sucessfully');
          }
          catch(Throwable $e){

            return redirect()->back()->with('error','credit not updated');
          }
          
        
        
      }


      public function history(Request $request){

       

        $payments=DB::table('credits_history');
      

        if($request->from !=null && $request->to !=null){
          $from = date($request->from );
           $to = date($request->to);
         
          $payments=$payments-> where('credits_history.created_at', '>=', $from)
          ->where('credits_history.created_at', '<=', $to);
        }

        $payments=$payments->join('users','credits_history.user_id','users.id') 
        ->where(
          function ($query) {
            $query->where('credits_history.payment_for', 'plan')
                  ->orWhere('credits_history.payment_for','credit');
        })
      
        ->select('credits_history.*','users.user_name','users.role')
      
        ->get();


      
     
        return view('admin.credit.history',compact('payments'));

      }

  
}



