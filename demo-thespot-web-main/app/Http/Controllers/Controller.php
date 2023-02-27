<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DB;
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


     public static function getNotificationList()
    {
         $fakeUsers=DB::table('users')->where('role','fake_user')->pluck('id')->toArray();
        // dd($fakeUsers);
         $chatsData=DB::table('chats')->whereIn('receiver_id',$fakeUsers)->orderBy('id', 'DESC')->get();
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
       
        return $chatsData;
        
    }
    public static function getNotificationCount()
    {
          $fakeUsers=DB::table('users')->where('role','fake_user')->pluck('id')->toArray();
        $chatsData=DB::table('chats')->whereIn('receiver_id',$fakeUsers)->where('is_read',0)->count();
         return $chatsData;
    }
}
