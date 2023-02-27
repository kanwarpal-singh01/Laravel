<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\NotificationTemplates;
use Illuminate\Http\Request;
use View;
use Session;
use Validator;
use Helper;
use App\User;
use DB;
class NotificationTemplatesController extends Controller
{
    public function index(Request $request){
       
        $pagelimit = Helper::get_config('AdminPageLimit');
        $notification = NotificationTemplates::orderBy('id', 'desc')->paginate($pagelimit);
        $usersList = User::where(['role' => 'users'])->where('name','<>',null)->get();
        return View::make('admin.notificationTemplates.index')->with(compact('notification', 'pagelimit','usersList'));
    }
    public function template_update(Request $request, $id){
        $notification = NotificationTemplates::findOrFail(base64_decode($id));
        if ($request->isMethod('PATCH')) {
            $validator = Validator::make($request->all(), [
                'title' => 'required|max:255',
                'message' => 'required|max:255',
            ]);
            if ($validator->fails()) {
                $error = Helper::WebValidationSet($validator->errors());
                if (!empty($error)) {
                    Session::flash('error', $error);
                    return back();
                }
            }
            $notification->Fill($request->all());
            $notification->save();
            if ($notification->save()) {
                Session::flash('success', 'Subscription has been changed');
                return redirect()->route('template_list');
            } else {
                Session::flash('error', 'Internal server error');
                return back();
            }
        }
       
        return View::make('admin.notificationTemplates.edit')->with(compact('notification'));
    }
    public function sendPushNotification(Request $request)
    {
        if($request->user_type == "paid"){
            $transactionUsers= DB::table('transactions')->get();
            foreach ($transactionUsers as $key => $value) {
             $userData=DB::table('users')->where('id',$value->user_id)->first();
             if($userData->token != null){
                $response['user_id']=$userData->id;
                $response['title']=$request->title;
                $response['body']=$request->message;
                $id= DB::table('notification')->insertGetId($response);
                $userCount=DB::table('notification')->where('user_id',$userData->id)->where('is_read',0)->count();
                 $fields = array(
                    "to" => $userData->token,
                    "notification" => [
                        "title" => $request->title,
                        "body" => $request->message,
                        "sound" => 'default', 
                        'badge' => $userCount
                    ],
                    "data"=>[
                        'type'=>'custom_notification',
                    ]
                 );
              
                 if($this->sendPushNotificationVal($fields) == true){
     
                 }
             }
            }
        }
        elseif($request->user_type == "unpaid"){
            $transactionUsers=  DB::table('transactions')->pluck('user_id');
            $userDataList=DB::table('users')->whereNotIn('id',$transactionUsers)->get();
            foreach ($userDataList as $key => $value) {
                $userData=DB::table('users')->where('id',$value->id)->first();
                if($userData->token != null){
                    $response['user_id']=$userData->id;
                    $response['title']=$request->title;
                    $response['body']=$request->message;
                    $id= DB::table('notification')->insertGetId($response);
                    $userCount=DB::table('notification')->where('user_id',$userData->id)->where('is_read',0)->count();
                    $fields = array(
                        "to" => $userData->token,
                        "notification" => [
                            "title" => $request->title,
                            "body" => $request->message,
                            "sound" => 'default', 
                            'badge' => $userCount
                        ],
                        "data"=>[
                            'type'=>'custom_notification',
                        ]
                    );
                 
                    if($this->sendPushNotificationVal($fields) == true){
        
                    }
                }
               }
        }
        else{
            if(isset($request->users) && count($request->users) >0){
                //    dd($request->users);die;
                    foreach ($request->users as $key => $value) {
                       
                        $userData=DB::table('users')->where('id',$value)->first();
                        
                        if($userData->token != null){
                            $response['user_id']=$userData->id;
                            $response['title']=$request->title;
                            $response['body']=$request->message;
                            $id= DB::table('notification')->insertGetId($response);
                            $userCount=DB::table('notification')->where('user_id',$userData->id)->where('is_read',0)->count();
                            $fields = array(
                                "to" => $userData->token,
                                "notification" => [
                                    "title" => $request->title,
                                    "body" => $request->message,
                                    "sound" => 'default', 
                                    'badge' => $userCount
                                ],
                                "data"=>[
                                    'type'=>'custom_notification',
                                ]
                            );
                           
                            if($this->sendPushNotificationVal($fields) == true){
        
                            }
                        }
                        
                    }
                    
                }
        }
       
       
        
        Session::flash('success', 'Notification sent successfully');
        return back();
      //  return redirect()->route('template_list');
    }
    private function sendPushNotificationVal($fields)
    {
     
         $SERVER_API_KEY = 'AAAA_Y1lIWI:APA91bHS3VLAmRPH3gHgV5UdN3fU36ZpeJWoRCvY9UjrLN-ms-NVHGi25tmfJLHGzVBfg2-q15imZXozocikWwMi_ix50tacs9SuVWMmxRQKLHPQ3Mg6J9GIVbAAlis22_5ZzNrHPU8i';
        $url = 'https://fcm.googleapis.com/fcm/send';
        $headers = array(
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        // dd($result);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        return true;
        curl_close($ch);
    }
}
