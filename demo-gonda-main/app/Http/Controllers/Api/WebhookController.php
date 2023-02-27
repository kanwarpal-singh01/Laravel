<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Notification;
use App\Model\UserTransaction;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class WebhookController extends Controller
{
    public function getInvoice(Request $request){

        if ($request->type == 'invoice.created'){
            $customer_id = $request->customer;
            $user = User::where('stripe_customer_id', $customer_id)->first();
            if ($user){
                $user_transaction = UserTransaction::where('user_id', $user->id)->first();
                if ($user_transaction){
                    $user_transaction->plan_start_date = Carbon::now();
                    $user_transaction->plan_end_date = Carbon::now()->addDay(30);
                    $user_transaction->save();
                    send_email($user, 'inv_create', 'inv_create');
                    $notification = new Notification();
                    $notification->comment = "Your monthly subscription has been created.";
                    $notification->type = 'invoice_created';
                    $notification->save();
                    //For Twilio
                    twilio_sms($user->country_code, $user->mobile_number, null, 'Your monthly subscription has been created.');
                }
            }
        }

        if ($request->type == 'invoice.payment_failed'){
            $customer_id = $request->customer;
            $user = User::where('stripe_customer_id', $customer_id)->first();
            if ($user){
                $user_transaction = UserTransaction::where('user_id', $user->id)->first();
                if ($user_transaction){
                    $user_transaction->delete();
                    send_email($user, 'inv_paym_failed', 'inv_paym_failed');
                    $notification = new Notification();
                    $notification->comment = "Your monthly subscription has been deleted.";
                    $notification->type = 'invoice_payment_failed';
                    $notification->save();
                    //For Twilio
                    twilio_sms($user->country_code, $user->mobile_number, null, 'Your monthly subscription has been deleted.');
                }
            }
        }

        if ($request->type == 'invoice.upcoming'){
            $customer_id = $request->customer;
            $user = User::where('stripe_customer_id', $customer_id)->first();
            if ($user){
                send_email($user, 'inv_upcoming', 'inv_upcoming');
                $notification = new Notification();
                $notification->comment = "Your monthly subscription has been expired soon.";
                $notification->type = 'invoice_upcoming';
                $notification->save();
                //For Twilio
                twilio_sms($user->country_code, $user->mobile_number, null, 'Your monthly subscription has been expired soon.');
            }
        }
    }
}
