<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Chats;
use App\Models\UserChats;
use App\Models\ViewChats;
use Illuminate\Http\Request;
use Helper;
use DB;
use Validator;
use Illuminate\Support\Carbon;

class ChatsController extends Controller
{
    public function chatList(Request $request)
    {

        $requestData = $request->all();
       
        if ($request->isMethod('Post')) {
            $requestData = $request->all();
          
            $chatList = ViewChats::select("*")
            ->where(function ($q) use ($requestData){
                $q->where('sender_id', $requestData['user_id']);
                $q->orWhere('receiver_id', $requestData['user_id']);
            });
           
            if(!empty($requestData['search'])){
                $chatList = $chatList->where(function ($q) use($requestData){
                    $q->where('sender_name', $requestData['search']);
                    $q->orWhere('receiver_name', 'like', '%' . $requestData['search'] . '%');
                });
            }
            $chatList = $chatList->get()->map->formatData($requestData['user_id'])->toArray();
           



            foreach ($chatList as $key => $value) {
                $story_user_id=$value['receiver_id'];
                if($requestData['user_id'] == $value['receiver_id']){
                    $story_user_id=$value['sender_id'];
                }
                $storyUserData=DB::table('users')->where(['id'=> $story_user_id])->first();


               $countStory=DB::table('user_stories')->where(['user_id'=> $story_user_id])->count();
                $chatData=DB::table('user_chats')->where(['id'=> $value['id']])->first();
               $value['story_status']=$countStory;
                $value['chat_status']=$chatData->chat_status;
               $chatList[$key]=$value;
               if($storyUserData->status == 5){

                     unset($chatList[$key]);
                    
                }
            }
            usort($chatList, function($b, $a) {
                return $a['created'] <=> $b['created'];
            });
            //sort($chatList);
         
            if (!empty($chatList)) {
                sendResponse('Chat List', 1, $chatList, ['profile_url' => config('app.profile_url')]);
            } else {
                //sendResponse('No chat found',0,$chatList);
                $response = ['status'=>0,'message'=>'No chat found','data'=>[]];
                 echo json_encode($response);die;
            }
        }
        sendResponse();
    }
    public function cmp($a, $b) {
                if ($a[1] == $b[1]) return 0;
                return (strtotime($a[1]) < strtotime($b[1])) ? 1 : -1;
            }
    public function getMessages(Request $request)
    {
        $requestData = $request->all();
        $chatMessages =  Chats::where(['room' => $requestData['room_id']])->orderBy('id','desc')->get()->toArray();
        if (!empty($chatMessages)) {
            sendResponse('Chat Messages', 1, $chatMessages, ['profile_url' => config('app.profile_url')]);
        } else {
            sendResponse('No Messages found');
        }
    }
    public function getChatPremiumStatus(Request $request)
    {
        $requestData = $request->all();
        $room='';
        if($requestData['user_id'] > $requestData['receiver_id']){
            $room=$requestData['receiver_id'].'_'.$requestData['user_id'];
        }
        if($requestData['receiver_id'] > $requestData['user_id']){
            $room=$requestData['user_id'].'_'.$requestData['receiver_id'];
        }
        $transactionData=DB::table('transactions')->where('user_id',$requestData['user_id'])->whereDate('expired_at', '>', Carbon::now())->first();

         if($transactionData){
             $loginUserData['subscription']=1;
         }else{
             $loginUserData['subscription']=0;
         }
        $chatList = DB::table('user_chats_premium')->select("*")
            ->where(function ($q) use ($requestData){
                $q->where('sender_id', $requestData['user_id']);
                $q->orWhere('receiver_id', $requestData['user_id']);
            })->groupBy('room')->get()->toArray();

           
            $loginUserData['totalCountToday']=0;
            $today = Carbon::now();
           // dd($today->toDateString());
           $updateChat=DB::table('user_chats_premium')->where(['room'=>$room])->first();
           if(empty($updateChat)){
            foreach ($chatList as $key => $value) {
            
                if(date('Y-m-d',strtotime($value->created_at))==$today->toDateString()){
                    $loginUserData['totalCountToday']=$loginUserData['totalCountToday']+1;
                }
                
            }
           }
           
            sendResponse('Chat List', 1, $loginUserData);
        
    }
    public function deleteChatByRoom(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'room_id' => 'required'
        ]);
        if ($validator->fails()) {
            sendResponse($validator->errors()->first());
        }
        $updateChat=DB::table('user_chats')->where(['room'=>$request->room_id])->first();
        if(!empty($updateChat)){
            DB::table('user_chats')->where('room', $request->input('room_id'))->delete();
            DB::table('chats')->where('room', $request->input('room_id'))->delete();
            sendResponse('Chat Deleted Successfully !');
        }else{
            sendResponse('No Chat Found');
        }  
    }
     public function deleteIncideChatByRoom(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'room_id' => 'required'
        ]);
        if ($validator->fails()) {
            sendResponse($validator->errors()->first());
        }
        $updateChat=DB::table('chats')->where(['room'=>$request->room_id])->first();
        if(!empty($updateChat)){
            DB::table('chats')->where('room', $request->input('room_id'))->delete();
            sendResponse('Chat Deleted Successfully !');
        }else{
            sendResponse('No Chat Found');
        }  
    }
}
