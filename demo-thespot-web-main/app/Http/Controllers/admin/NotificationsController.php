<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Notification;
use View;
use Session;
use Validator;
use Helper;
use DB;
class NotificationsController extends Controller
{
	//show email templates with filter
    public function index(Request $request){
    	// $query = $request->input('q');
        // if ($query) {
        //    $records = EmailTemplate::orderBy('id', 'ASC')->where('status','=',1)->where('title', 'LIKE', "%$query%")->orwhere('subject', 'LIKE', "%$query%")->orwhere('content', 'LIKE', "%$query%")->orWhere('slug','LIKE',"%$query%")->paginate(config('Settings.AdminPageLimit'));
        //     return view('admin/emailtemplate.index',  array('records' => $records,'query' => $query,'title' => 'Cms Page'));  
        
        // } else {
          
            // $fakeUsers=DB::table('users')->where('role','fake_user')->pluck('id')->toArray();
            // // dd($fakeUsers);
            //  $chatsData=DB::table('chats')->whereIn('receiver_id',$fakeUsers)->orderBy('id', 'DESC')->get();
            //  foreach ($chatsData as $key => $value) {
            //     $userData=DB::table('users')->where('id',$value->sender_id)->first();
            //     $receiverData=DB::table('users')->where('id',$value->receiver_id)->first();
            //     if(!empty($userData) && !empty($receiverData)){
            //         $value->user_name=$userData->name;
            //         $value->receiver_name=$receiverData->name;
            //         $value->profile_image=config('app.profile_url').$userData->profile_image;
            //         if($userData->role='fake_user'){
            //             $chatsData[$key]=$value;
            //         }
                     
            //     }else{
            //         $value->user_name="";
            //         $value->receiver_name="";
            //         $value->profile_image=config('app.profile_url').'no_image.jpg';
            //          $chatsData[$key]=$value;
            //     }
                
               
            //  }

             
            //  $chatsData->paginate(config('Settings.AdminPageLimit'));
           return view('admin/notifications.index');  
        
       // }
    }




    public static function getNoti(Request $request)
    {

        $query = $request->input('q');


         $fakeUsers=DB::table('users')->where('role','fake_user')->pluck('id')->toArray();
        // dd($fakeUsers);
         $chatsData=DB::table('chats')->orderBy('chats.id', 'DESC')->whereIn('chats.receiver_id',$fakeUsers)->paginate(10);
         foreach ($chatsData as $key => $value) {
            $userData=DB::table('users')->where('id',$value->sender_id)->first();
            $receiverData=DB::table('users')->where('id',$value->receiver_id)->first();
            if(!empty($userData) && !empty($receiverData)){
                $value->user_name=$userData->name;
                $value->receiver_name=$receiverData->name;
                $value->profile_image=config('app.profile_url').$userData->profile_image;
                if($userData->role='fake_user'){
                    $chatsData[$key]=$value;
                }
                 
            }else{
                $value->user_name="";
                $value->receiver_name="";
                $value->profile_image=config('app.profile_url').'no_image.jpg';
                 $chatsData[$key]=$value;
            }
           
            
         }
       
         $records = $chatsData;

    
         //dd($records);
        return view('admin/notifications/index',compact('records','query'));  

        
    }
    public static function getNotificationCount()
    {
          $fakeUsers=DB::table('users')->where('role','fake_user')->pluck('id')->toArray();
        $chatsData=DB::table('chats')->whereIn('receiver_id',$fakeUsers)->where('is_read',0)->count();
         return $chatsData;
    }





    
}
