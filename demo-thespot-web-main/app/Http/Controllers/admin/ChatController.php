<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserChats;
use App\User;
use Illuminate\Http\Request;
use DB;
use Auth;
class ChatController extends Controller
{
    public function index(){
        $chatsList = User::where(['role'=>'fake_user'])->select(['id','profile_image','name'])->paginate(10);
        // $chatsList = UserChats::with('sender', 'receiver')->whereIn('id',$ids)->get()->map->formatData();
        // pr($chatsList);die;
        
        //pr($chatsList);die;
        return view('admin.chats.index', compact('chatsList'));
    }
    public function chatting(Request $request,$user_id){
        $query = $request->input('chatid');

        $chatsList = UserChats::with('sender', 'receiver')->where(['sender_id'=> $user_id])
                        ->orWhere(['receiver_id'=>$user_id])
                        ->orderBy('id','desc')->get()->map->formatData();

        //echo '<pre>'; print_r($chatsList); die;
        $chatsDataById="";
        if($query){
            $updateSpyMode = DB::table('chats')->where(['id'=> $query])->update(['is_read'=>1]);
            $chatsDataById = DB::table('chats')->where(['id'=> $query])->first();
             $senderData=DB::table('users')->where('id',$chatsDataById->sender_id)->first();
            $receiverData=DB::table('users')->where('id',$chatsDataById->receiver_id)->first();
            $chatsDataById->sender_name="";
            $chatsDataById->receiver_name="";
            if(!empty($senderData)){
                $chatsDataById->sender_name=$senderData->name;
            }
            if(!empty($receiverData)){
                 $chatsDataById->receiver_name=$receiverData->name;
            }
            
           
        }
        foreach ($chatsList as $key => $value) {
            $value['is_liked']=0;
            $likeunlikesData = DB::table('like_unlikes')->where(['like_user_id'=> $value['sender_id'],'user_id'=>$user_id,'status'=>1,'type'=>'Like'])->first();
            if(!empty($likeunlikesData)){
                $value['is_liked']=1;
            }
            $chatsList[$key]=$value;
        }
        foreach ($chatsList as $key => $value) {
            $value['is_viewed']=0;
            $likeunlikesData = DB::table('like_unlikes')->where(['like_user_id'=> $value['sender_id'],'user_id'=>$user_id,'status'=>1,'type'=>'View'])->first();
            if(!empty($likeunlikesData)){
                $value['is_viewed']=1;
            }
            $chatsList[$key]=$value;
        }
        // dd($chatsList);
        return view('admin.chats.chatting', compact('chatsList','user_id','chatsDataById'));
    }
    
}
