<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\HomeSection6;
use App\Model\Notification;
use App\Model\UserTransaction;
use App\User;
use Carbon\Carbon;
use Google_Client;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\EphemeralKey;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\StripeClient;

class StripePaymentController extends Controller
{
    private $stripe;
    public function __construct()
    {
        $this->stripe = new StripeClient(env('STRIPE_SECRET'));
    }

    public static function createStripeCustomer($name, $mobile_number)
    {

        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );

        $customer = $stripe->customers->create([
            'name' => $name,
            'phone' => $mobile_number,
        ]);

        return $customer;
    }

    public function createCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
//        $stripe = new StripeClient(
//            env('STRIPE_SECRET')
//        );
        $user = User::where('id', auth('api')->id())->first();
        if (!empty($user)) {

            $cart_list = $this->stripe->customers->allSources(
                $user->stripe_customer_id
            );

            if (count($cart_list) == 0) {

                $data = $this->stripe->customers->update(
                    $user->stripe_customer_id,
                    ['source' => $request['token']]
                );
            } else {
                $data = $this->stripe->customers->createSource(
                    $user->stripe_customer_id,
                    ['source' => $request['token']]
                );
            }

            $latest_card_list = $this->stripe->customers->allSources(
                $user->stripe_customer_id
            );
            return response()->json([
                'status' => 1,
                'message' => 'Your card has been added.',
                'data' => $data,
                'cart_listing' => $latest_card_list,
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ]);
    }

    public static function cardList()
    {
        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );
        $user = User::where('id', auth('api')->id())->first();

        if (!empty($user)) {

            $data = $stripe->customers->allSources(
                $user->stripe_customer_id,
                ['object' => 'card']
            );
            return $data;
        }
    }

    public function deleteCard(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );
        $user = User::where('id', auth('api')->id())->first();

        if (!empty($user)) {
            $data = $stripe->customers->deleteSource(
                $user->stripe_customer_id,
                $request['token'],
                []
            );

            if ($data->deleted == true) {
                $message = 'Your card has been deleted.';
            } else {
                $message = 'Something went wrong.';
                $data = [];
            }
            return response()->json([
                'status' => 1,
                'message' => $message,
                'data' => $data,
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ]);
    }

    public function stripe(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'plan_id' => 'required|exists:home_section6s,id',
//            'promo_code' => 'required|exists:coupons',
//        ]);
//        if ($validator->fails()){
//            $error = $validator->errors()->first();
//            return response()->json([
//                "status" => 0,
//                "message" => $error,
//            ]);
//        }
//        $plan = HomeSection6::where(['id' => $request['plan_id'], 'status' => 1])->first();
//
//        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));
//
//        $stripe = new StripeClient(
//            env('STRIPE_SECRET')
//        );
////        $token  =  $stripe->tokens->create([
////            'card' => [
////                'number' => $request['number'],
////                'exp_month' => $request['exp_month'],
////                'exp_year' => $request['exp_year'],
////                'cvc' => $request['cvc'],
////            ],
////        ]);
//        $Tncdetail= Charge::create ([
//            "amount" => ($plan->price) * 100,
//            "currency" => "usd",
//            "source" => $request['token'],
//            //"source" => $token->id,
//            "description" => "Test payment from gonda.com."
//        ]);
//        if ($Tncdetail->status == 'succeeded'){
//            $user_transaction = UserTransaction::where(['user_id' => auth('api')->id(),
//                                    'plan_id'=>$plan->id])->first();
//            if (empty($user_transaction)) {
//                $user_transaction = new UserTransaction();
//                $user_transaction->user_id = auth('api')->id();
//                $user_transaction->transaction_id = $Tncdetail->balance_transaction;
//                $user_transaction->plan_id = $request['plan_id'];
//                $user_transaction->plan_name = $plan->title;
//                $user_transaction->credit_points = $plan->credit_points ?? 0;
//                $user_transaction->status = 0;
//                $user_transaction->save();
//            }
//                $user_transaction->user_id = auth('api')->id();
//                $user_transaction->transaction_id = $Tncdetail->balance_transaction;
//                $user_transaction->plan_id = $request['plan_id'];
//                $user_transaction->plan_name = $plan->title;
//                $user_transaction->credit_points += $plan->credit_points;
//                $user_transaction->status = 1;
//                $user_transaction->save();
//        }
//        return response()->json([
//            'status' => 1,
//            'message' => 'Payment successful!',
//            'data' => $Tncdetail,
//            'user_transaction' => $user_transaction
//        ]);

        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:home_section6s,id',
            //'promo_code' => 'sometimes|nullable|exists:coupons',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }

        $stripe = Stripe::setApiKey(env('STRIPE_SECRET'));

        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );
        $plan_id = $request['plan_id'];
        $promo_code = $request['promo_code'];
        $user = User::where('id', auth('api')->id())->first();

        $coupon = Coupon::where('promo_code', $promo_code)->first();

        $plan = HomeSection6::where('id', $plan_id)->first();

        $strip_id = [];

        $is_valid_promo_code = Coupon::isValidPromoCode($promo_code);

        if (!empty($plan) && !empty($coupon)) {
            $discount = ($coupon->discount / 100) * $plan->price;
            $actual_pay = $plan->price - $discount;
        } else {
            $actual_pay = $plan->price;
        }
        if (!empty($user)) {
            $strip_id = $user->stripe_id;
        }
        $ephemeralKey = EphemeralKey::create(
            ['customer' => $strip_id],
            ['stripe_version' => '2020-08-27']
        );
        $paymentIntent = PaymentIntent::create([
            'amount' => $actual_pay * 100,
            'currency' => 'usd',
            'customer' => $strip_id,
        ]);
        return response()->json([
            'status' => 1,
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $strip_id,
            'discount' => $discount ?? 0,
            'actual_pay' => $actual_pay,
            'is_valid_promo_code' => $is_valid_promo_code,
        ]);
    }

    public function createStripSubscription(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:home_section6s,id',
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $stripe = new StripeClient(
            env('STRIPE_SECRET')
        );

        $plan_id = $request['plan_id'];

        $user = User::where('id', auth('api')->id())->first();

        $plan = HomeSection6::where('id', $plan_id)->first();

        $promo_code_apply = '';
        if (!empty($request['promo_code'])) {
            $coupon = Coupon::where('promo_code', $request['promo_code'])->first();
            if (!empty($coupon)) {
                $promo_code_apply = $coupon->stripe_coupon_id;
            }
        }

        if (!empty($user) && !empty($plan)) {

            $add_card = $stripe->customers->update(
                $user->stripe_customer_id,

                ['invoice_settings' =>
                    ['default_payment_method' => $request['token']],
                ]
            );

            $subscription_data = $stripe->subscriptions->create([
                'customer' => $user->stripe_customer_id,
                'coupon' => $promo_code_apply,
                'items' => [
                    ['price' => $plan->stripe_plan_id],

                ],
            ]);
            if ($subscription_data->status == 'active') {
                $user_transaction = UserTransaction::where('user_id', auth('api')->id())->first();
                $user = User::where('id', auth('api')->id())->first();
                if (empty($user_transaction)) {
                    $user_transaction = new UserTransaction();
                    $user_transaction->user_id = auth('api')->id();
                    $user_transaction->stripe_subscription_id = $subscription_data->id;
                    $user_transaction->plan_id = $request['plan_id'];
                    $user_transaction->plan_name = $plan->title;
                    $user_transaction->plan_start_date = Carbon::now();
                    $user_transaction->plan_end_date = Carbon::now()->addDays(30);
                    $user_transaction->credit_points = $plan->credit_points ?? 0;
                    $user_transaction->status = 1;
                    $user_transaction->save();
                    $user->total_credit_points = $user_transaction->credit_points ?? 0;
                    if ($user_transaction->plan_id != 3) {
                        $user->is_premium = 0;
                    } else {
                        $user->is_premium = 1;
                    }
                    $user->save();
                }
                $user_transaction->user_id = auth('api')->id();
                $user_transaction->stripe_subscription_id = $subscription_data->id;
                $user_transaction->plan_id = $request['plan_id'];
                $user_transaction->plan_name = $plan->title;
                $user_transaction->credit_points += $plan->credit_points;
                $user_transaction->plan_start_date = Carbon::now();
                $user_transaction->plan_end_date = Carbon::now()->addDays(30);
                $user_transaction->status = 1;
                $user_transaction->save();
                $user->total_credit_points = $user_transaction->credit_points ?? 0;
                if ($user_transaction->plan_id != 3) {
                    $user->is_premium = 0;
                } else {
                    $user->is_premium = 1;
                }
                $user->save();
                $message = 'You have subscribed to our "' . strtoupper($user_transaction['plan_name']) . '" plan';
                $status = 1;
            } else {
                $subscription_data = [];
                //$message = 'You do not have sufficient balance or card issue or something went wrong.';
                $message = 'Oops, payment could not be completed. Please confirm your payment details to continue.';
                $status = 0;
            }
            $user_transaction['is_premium'] = $user->is_premium;

            if (auth('api')->user()->email_notification == 1) {
                $notification = new Notification();
                $notification->comment = $message;
                $notification->type = 'subscription';
                $notification->user_id = auth('api')->id();
                $notification->save();
            }
            //for push notification
//            $msg = array(
//                'body' => $message,
//                'title' => 'goNDA',
//                'type' => 'subscription',
//                //'document' => $document->id,
//                'subtitle' => $message,
//                'key' => '5',
//                'vibrate' => 1,
//                'sound' => 1,
//                'largeIcon' => 'large_icon',
//                'smallIcon' => 'small_icon'
//            );
//            $push_notification = push_notification_send_driver(auth('api')->user()->device_token, $msg);
            //for push notification
            return response()->json([
                'status' => $status,
                //'push_notification' => $push_notification,
                'message' => $message,
                'data' => $subscription_data,
                'current_plan' => $user_transaction,
            ]);
        }

        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ]);
    }

    public function applyPromoCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:home_section6s,id',
            //'promo_code' => 'required|exists:coupons',
            'promo_code' => 'required',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $plan_id = $request['plan_id'];

        $promo_code = $request['promo_code'];

        $coupon = Coupon::where('promo_code', $promo_code)->first();

        $plan = HomeSection6::where('id', $plan_id)->first();

        $is_valid_promo_code = Coupon::isValidPromoCode($promo_code);

        if (!empty($plan) && !empty($coupon)) {
            $discount = ($coupon->discount / 100) * $plan->price;
            $actual_pay = $plan->price - $discount;
        } else {
            $actual_pay = $plan->price;
        }
        return response()->json([
            'status' => 1,
            'discount' => $discount ?? 0,
            'actual_pay' => $actual_pay,
            'is_valid_promo_code' => $is_valid_promo_code,
        ]);
    }

    public function deleteSubscription(Request $request)
    {
        try {
            $user_transaction = UserTransaction::where('user_id', auth('api')->id())->first();

            if ($user_transaction) {

                if (auth('api')->user()->email_notification == 1) {
                    $notification = new Notification();
                    $notification->comment = 'You have unsubscribed from our "' . strtoupper($user_transaction->plan_name) . '" plan';
                    $notification->type = 'unsubscribe';
                    $notification->user_id = auth('api')->id();
                    $notification->save();
                }

                //for push notification
//                if (auth('api')->user()->push_notification == 1) {
//                    $msg = array(
//                        'body' => 'You have unsubscribed from our "' . strtoupper($user_transaction->plan_name) . '" plan',
//                        'title' => 'goNDA',
//                        'type' => 'subscription',
//                        //'document' => $document->id,
//                        'subtitle' => 'You have unsubscribed from our "' . strtoupper($user_transaction->plan_name) . '" plan',
//                        'key' => '5',
//                        'vibrate' => 1,
//                        'sound' => 1,
//                        'largeIcon' => 'large_icon',
//                        'smallIcon' => 'small_icon'
//                    );
//                    $push_notification = push_notification_send_driver(auth('api')->user()->device_token, $msg);
//                }
                //for push notification
                $this->stripe->subscriptions->cancel(
                    $user_transaction->stripe_subscription_id
                );

                $user_transaction->delete();

                return response()->json([
                    'status' => 1,
                    // 'push_notification' => $push_notification,
                    'message' => 'You have unsubscribed.',
                    'data' => [],
                ]);
            }
            return response()->json([
                'status' => 1,
                'message' => 'No Records',
                'data' => [],
            ]);
        } catch (\Exception$exception) {
            return error_response($exception);
        }
    }

    //without stripe
    public function createSubscription(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'plan_id' => 'sometimes|nullable|exists:home_section6s,id',
            'type_ios' => 'sometimes|nullable|exists:home_section6s,type_ios',
            'type_android' => 'sometimes|nullable|exists:home_section6s,type_android',
            'transaction_id' => 'sometimes|nullable',
        ]);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }

        $plan_id = $request['plan_id'];
        $type_ios = $request['type_ios'];
        $type_android = $request['type_android'];
        $user = User::where('id', auth('api')->id())->first();

        if ($request['android_data'] != null) {

            $client = new Google_Client();

            $root_path = public_path('jsonfile.json');

            $client->setAuthConfig($root_path);

            $client->addScope('https://www.googleapis.com/auth/androidpublisher');

            $service = new \Google_Service_AndroidPublisher($client);

            $androidData = json_decode($request['android_data']);

            $purchase = $service->purchases_subscriptions->get($androidData->packageName, $androidData->productId, $androidData->purchaseToken);

            if ($purchase->orderId) {
                $plan = HomeSection6::where('id', $plan_id)->first();
                if (!empty($plan)) {
                    $user_transaction = UserTransaction::where('user_id', auth('api')->id())->first();
                    $user = User::where('id', auth('api')->id())->first();
                    if (empty($user_transaction)) {
                        $user_transaction = new UserTransaction();
                        $user_transaction->user_id = auth('api')->id();
                        $user_transaction->plan_id = $plan->id;
                        $user_transaction->transaction_id = $purchase->orderId;
                        $user_transaction->plan_name = $plan->title;
                        $user_transaction->plan_start_date = Carbon::now();
                        $user_transaction->plan_end_date = $plan->title_slug == "adhoc" ? Carbon::now()->addDays(60) : Carbon::now()->addDays(30);
                        $user_transaction->credit_points = $plan->credit_points ?? 0;
                        $user_transaction->status = 1;
                        $user_transaction->save();
                        $user->total_credit_points = $user_transaction->credit_points ?? 0;
                        if ($user_transaction->plan_id != 3) {
                            $user->is_premium = 0;
                        } else {
                            $user->is_premium = 1;
                        }
                        $user->save();

                    } else {
                        $user_transaction->user_id = auth('api')->id();
                        $user_transaction->plan_id = $plan->id;
                        $user_transaction->transaction_id = $purchase->orderId;
                        $user_transaction->plan_name = $plan->title;
                        $user_transaction->credit_points += $plan->credit_points;
                        $user_transaction->plan_start_date = Carbon::now();
                        $user_transaction->plan_end_date = $plan->title_slug == "adhoc" ? Carbon::now()->addDays(60) : Carbon::now()->addDays(30);
                        $user_transaction->status = 1;
                        $user_transaction->save();
                        $user->total_credit_points = $user_transaction->credit_points ?? 0;
                        if ($user_transaction->plan_id != 3) {
                            $user->is_premium = 0;
                        } else {
                            $user->is_premium = 1;
                        }
                        $user->save();
                    }
                    $message = 'You have subscribed to our "' . strtoupper($user_transaction['plan_name']) . '" plan';
                    $status = 1;
                    $user_transaction['is_premium'] = $user->is_premium;

                    if (auth('api')->user()->email_notification == 1) {
                        $notification = new Notification();
                        $notification->comment = $message;
                        $notification->type = 'subscription';
                        $notification->user_id = auth('api')->id();
                        $notification->save();
                    }
                    return response()->json([
                        'status' => $status,
                        'message' => $message,
                        'current_plan' => $user_transaction,
                    ]);
                } else {
                    return response()->json([
                        'status' => 0,
                        'message' => 'No Plan',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 0,
                    'message' => 'Something went wrong.Please try again.',
                ]);
            }
        } else {
            //$plan = HomeSection6::where('type_ios', $type_ios)->orWhere('type_android', $type_android)->first();
            $plan = HomeSection6::where('id', $plan_id)->first();
            if (!empty($plan) && !empty($request['transaction_id'])) {
                $user_transaction = UserTransaction::where('user_id', auth('api')->id())->first();
                $user = User::where('id', auth('api')->id())->first();
                if (!empty($user_transaction->transaction_id) && $user_transaction->transaction_id == $request['transaction_id']) {
                    $status = 1;
                    $message = 'You have already subscribed "' . strtoupper($user_transaction['plan_name']) . '" plan';
                } else {
                    if (empty($user_transaction)) {
                        $user_transaction = new UserTransaction();
                        $user_transaction->user_id = auth('api')->id();
                        //$user_transaction->stripe_subscription_id = $subscription_data->id;
                        $user_transaction->plan_id = $plan->id;
                        $user_transaction->transaction_id = $request['transaction_id'];
                        $user_transaction->plan_name = $plan->title;
                        $user_transaction->plan_start_date = Carbon::now();
                        $user_transaction->plan_end_date = $plan->title_slug == "adhoc" ? Carbon::now()->addDays(60) : Carbon::now()->addDays(30);
                        $user_transaction->credit_points = $plan->credit_points ?? 0;
                        $user_transaction->status = 1;
                        $user_transaction->save();
                        $user->total_credit_points = $user_transaction->credit_points ?? 0;
                        if ($user_transaction->plan_id != 3) {
                            $user->is_premium = 0;
                        } else {
                            $user->is_premium = 1;
                        }
                        $user->save();
                    } else {
                        $user_transaction->user_id = auth('api')->id();
                        $user_transaction->plan_id = $plan->id;
                        $user_transaction->transaction_id = $request['transaction_id'];
                        $user_transaction->plan_name = $plan->title;
                        $user_transaction->credit_points += $plan->credit_points;
                        $user_transaction->plan_start_date = Carbon::now();
                        $user_transaction->plan_end_date = $plan->title_slug == "adhoc" ? Carbon::now()->addDays(60) : Carbon::now()->addDays(30);
                        $user_transaction->status = 1;
                        $user_transaction->save();
                        $user->total_credit_points = $user_transaction->credit_points ?? 0;
                        if ($user_transaction->plan_id != 3) {
                            $user->is_premium = 0;
                        } else {
                            $user->is_premium = 1;
                        }
                        $user->save();
                    }
                    $message = 'You have subscribed to our "' . strtoupper($user_transaction['plan_name']) . '" plan';
                    $status = 1;
                }
                $user_transaction['is_premium'] = $user->is_premium;

                if (auth('api')->user()->email_notification == 1) {
                    $notification = new Notification();
                    $notification->comment = $message;
                    $notification->type = 'subscription';
                    $notification->user_id = auth('api')->id();
                    $notification->save();
                }
                return response()->json([
                    'status' => $status,
                    'message' => $message,
                    'current_plan' => $user_transaction,
                ]);
            }
        }
        return response()->json([
            'status' => 0,
            'message' => 'Something went wrong.',
        ]);
    }

}
