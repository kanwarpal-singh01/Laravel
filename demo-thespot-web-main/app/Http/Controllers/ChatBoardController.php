<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\PrivateMessage;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use Helper;

class ChatBoardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
       $role=Helper::get_user_role(Auth::id());
       if($role=='employer'){
         
        $users = DB::select("select users.id, users.user_name, users.profile_image, users.email, count(is_read) as unread 
        from users
        INNER  JOIN  engagement_request ON users.id = engagement_request.req_id and engagement_request.status = 1 and engagement_request.emp_id = " . Auth::id() . "
        
        
        LEFT  JOIN  messages ON users.id = messages.from and is_read = 0 and messages.to = " . Auth::id() . "
        where users.id!=1 AND users.role ='recruiter' OR  users.role ='sub-recruiter' 
        group by users.id, users.user_name, users.profile_image, users.email");

       }
       elseif($role=='recruiter' || $role=='sub-recruiter'){

        $users = DB::select("select users.id, users.user_name, users.profile_image, users.email, count(is_read) as unread 
        from users
        INNER  JOIN  engagement_request ON users.id = engagement_request.emp_id and engagement_request.status = 1 and engagement_request.req_id = " . Auth::id() . "

        LEFT  JOIN  messages ON users.id = messages.from and is_read = 0 and messages.to = " . Auth::id() . "
        where users.id!=1 AND users.role ='employer'
        group by users.id, users.user_name, users.profile_image, users.email");

       }

      



        foreach($users as $user ){
            $dt=DB::table('messages')->where('to',$user->id)->where('from',Auth::id())->orderBy('id', 'desc')->first();

             if(isset($dt->message) && isset($dt->created_at)){
                $user->message=$dt->message;
                $user->last_msg_time=$dt->created_at;
             }
             else{
                $user->message='';
                $user->last_msg_time='';
             }

           
        }


  
  

        return view('front.chat_board.home', ['users' => $users]);
    }

    public function getMessage($user_id)
    {

        $my_id = Auth::id();

        // Make read all unread message
        Message::where(['from' => $user_id, 'to' => $my_id])->update(['is_read' => 1]);

        // Get all message from selected user
        $messages = Message::where(function ($query) use ($user_id, $my_id) {
            $query->where('from', $user_id)->where('to', $my_id);
        })->oRwhere(function ($query) use ($user_id, $my_id) {
            $query->where('from', $my_id)->where('to', $user_id);
        })->get();

        $user=User::find($user_id);

        return view('front.chat_board.messages.index', ['messages' => $messages,'user'=>$user]);
    }

    public function getMessagePrivate($user_id,$candidate_id)
    {

        $my_id = Auth::id();

        PrivateMessage::where(['from' => $user_id, 'to' => $my_id,'candidate_id'=> $candidate_id])->update(['is_read' => 1]);

        $messages = PrivateMessage::where(function ($query) use ($user_id, $my_id,$candidate_id) {
            $query->where('from', $user_id)->where('to', $my_id)->where('candidate_id', $candidate_id);
        })->oRwhere(function ($query) use ($user_id, $my_id,$candidate_id) {
            $query->where('from', $my_id)->where('to', $user_id)->where('candidate_id',$candidate_id);
        })->get();


        return view('front.chat_board.messages.index', ['messages' => $messages]);
    }

    public function sendMessage(Request $request)
  {

    $options = array(
        'cluster' => 'ap2',
        'useTLS' => true
    );

    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        $options
    );

      
      $from = Auth::id();
      $to = $request->receiver_id;
      $message = $request->message;

       $file_path='';
      if($request->hasFile('file')) {
               
      
          $file = $request->file('file');

          $name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

          
          $file->move(public_path().'/uploads/', $name);

          $file_path=$name;
   
       }

       if($request->type=='private'){
        $data = new PrivateMessage();
        $data->from = $from;
        $data->to = $to;
        $data->candidate_id = $request->candidate_id;
        $data->message = $message;
        $data->file=$file_path;
        $data->is_read = 0; // message will be unread when sending message
        $data->save();

        $data = ['from' => $from, 'to' => $to,'candidate'=>$request->candidate_id];
        $pusher->trigger('secure-channel', 'secure-event', $data);

        
       }else{
        $data = new Message();
        $data->from = $from;
        $data->to = $to;
        $data->message = $message;
        $data->file=$file_path;
        $data->is_read = 0; // message will be unread when sending message
        $data->save();

        $data = ['from' => $from, 'to' => $to,'message'=>$message];
        $pusher->trigger('my-channel', 'my-event', $data);
       }
    

      // pusher
   

  
  }

}
