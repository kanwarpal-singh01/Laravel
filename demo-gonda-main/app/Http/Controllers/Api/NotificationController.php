<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function notification(Request $request){
        $validator = Validator::make($request->all(), [
            'push_notification' => 'required|in:0,1',
            'email_notification' => 'required|in:0,1',
        ]);
        if ($validator->fails()){
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $push_notification = $request['push_notification'];
        $email_notification = $request['email_notification'];
        try {
            $user = User::where('id', auth('api')->id())->first();
            if (!empty($user)) {
                $user->push_notification = $push_notification;
                $user->email_notification = $email_notification;
                $user->save();
                return success_response($user, 'Notification successfully updated');
            }
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }

    public function allNotificationList(){
        try{
            $notification = Notification::where('user_id', auth('api')->id())->orderBy('created_at', 'desc')->paginate(10);
            if ($notification->isNotEmpty()){
                return success_response($notification, 'All notification list');
            }
            return response()->json([
                'status' => 1,
                'message' => 'No Records',
                'data' => []
            ]);
        }
        catch (\Exception $exception){
            return error_response($exception);
        }

    }
}
