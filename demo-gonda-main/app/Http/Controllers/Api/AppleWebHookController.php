<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\UserTransaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AppleWebHookController extends Controller
{
    public function appleWebHook(Request $request){

        Log::debug($request->notification_type);

        Log::debug($request->all());
        try {
            if ($request->notification_type == 'DID_CHANGE_RENEWAL_STATUS')
            {
                if ($request['unified_receipt']['pending_renewal_info'][0]['auto_renew_status'] == 0)
                {
                    Log::debug('cancel');
                    $transaction = $request['unified_receipt']['latest_receipt_info'][0]['transaction_id'];
                    Log::debug($transaction);
                    $user_transaction =  UserTransaction::where('transaction_id', $transaction)->first();
                    Log::debug($user_transaction);
                    if ($user_transaction){
                        Log::debug('data deleted 43');
                        $user = User::where('id', $user_transaction->user_id)->first();
                    if ($user){

                        $user->total_credit_points = 0;
                        $user->save();

                        //send_email($user, 'cancel', 'cancel');
                        $notification = new Notification();
                        $notification->comment = "Your plan has been cancelled";
                        $notification->type = 'apple_web_hook_cancel';
                        $notification->user_id = $user->id;
                        $notification->save();
                        Log::debug('notification create');

                        $user_transaction->delete();

                        Log::debug('user transaction deleted');
                    }
                    else{
                        $message = 'No user found.';
                    }
                    }
                    else{
                        $message = 'Invalid transaction id.';
                    }
                }
            }
        }
        catch (\Exception $exception){
            return $exception->getMessage();
        }

    }
}
