<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\CompanyDetail;
use App\Model\Contact;
use App\Model\Document;
use App\Model\MaillingAddress;
use App\Model\Notification;
use App\User;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class ProfileSetupController extends Controller
{
    public function profileSetup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //'user_id' => 'required|exists:users,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'profile_image' => 'mimes:jpg,jpeg,png,svg|max:50000',
            'sign_image' => 'required|string',
            'secret_pin' => 'required|digits_between:4,6|confirmed',
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'apartment_number' => 'sometimes|nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_name' => 'required|string|max:255',
            'zip_code' => 'required|digits_between:5,6',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }

        $user = User::with('document')->where('id', auth('api')->id())->first();
        $name = $request['first_name'] . " " . $request['last_name'];
        $mobile_number = $user['mobile_number'];
        $company_datail = CompanyDetail::where('user_id', auth('api')->id())->first();
        $mailling_address = MaillingAddress::where('user_id', auth('api')->id())->first();
        if (!empty($user)) {
            $strip = StripePaymentController::createStripeCustomer($name, $mobile_number);
            $user->stripe_customer_id = $strip->id;
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->role = 3;
            //$user->secret_pin = Hash::make($request['secret_pin']);
            $user->secret_pin = $request['secret_pin'];
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                //$files = Image::make($images)->resize(1800, 1200)->save();
                if ($file) {
                    $destinationPath = 'public/storage/uploads/user-profile/';
                    $extension = $request->file('profile_image')->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $user->profile_image = $filename;
                }
            }
            if ($request->has('sign_image')) {
                $sign = str_replace(' ', '+', $request['sign_image']);
                $base_64_decoded_image = base64_decode($request['sign_image']);
                $destinationPath = 'public/storage/uploads/user-sign/';
                $filename = uniqid() . "_sign.jpg";
                file_put_contents($destinationPath . $filename, $base_64_decoded_image);
                $user->sign_image = $filename;
            }

//            $image = $request->sign_image;  // your base64 encoded
//            $image = str_replace('data:image/png;base64,', '', $image);
//            $image = str_replace(' ', '+', $image);
//            $imageName = uniqid(). "_sign2.png";
//            File::put(storage_path(). '/' . $imageName, base64_decode($image));
//            $user->sign_image = $imageName;

//            if ($request->has('sign_image')) {
//                $base_64_decoded_image = base64_decode($request['sign_image']);
//                $destinationPath = 'public/storage/uploads/user-sign/';
//                $filename =  uniqid(). "_sign.jpg";
//                file_put_contents($destinationPath.$filename, $base_64_decoded_image);
//                $user->sign_image = $filename;
//            }
            $user->save();
        }
        if (empty($company_datail)) {
            $company_datail = new CompanyDetail();
            $company_datail->user_id = auth('api')->id();
            $company_datail->company_name = $request['company_name'];
            $company_datail->job_title = $request['job_title'];
        }
        $company_datail->user_id = auth('api')->id();
        $company_datail->company_name = $request['company_name'];
        $company_datail->job_title = $request['job_title'];
        $company_datail->save();

        if (empty($mailling_address)) {
            $mailling_address = new MaillingAddress();
            $mailling_address->user_id = auth('api')->id();
            $mailling_address->street_address = $request['street_address'];
            $mailling_address->apartment_number = $request['apartment_number'];
            $mailling_address->country_id = $request['country_id'];
            $mailling_address->state_id = $request['state_id'];
            $mailling_address->city_name = $request['city_name'];
            $mailling_address->zip_code = $request['zip_code'];
        }
        $mailling_address->user_id = auth('api')->id();
        $mailling_address->street_address = $request['street_address'];
        $mailling_address->apartment_number = $request['apartment_number'];
        $mailling_address->country_id = $request['country_id'];
        $mailling_address->state_id = $request['state_id'];
        $mailling_address->city_name = $request['city_name'];
        $mailling_address->zip_code = $request['zip_code'];
        $mailling_address->save();

        $user_data = json_encode($user);

        $contact_id = Contact::where('contact_mobile_number', $user->mobile_number)->pluck('id');
        if ($contact_id) {
            $document = Document::whereIn('contact_id', $contact_id)->update(['receiver_details' => $user_data]);

        }

        $data = [
            'user_data' => $user,
            'company_detail' => $company_datail,
            'mailing_address' => $mailling_address,
        ];
        $notification = new Notification();
        $notification->comment = 'Your profile setup is completed.';
        $notification->type = 'profile';
        $notification->user_id = auth('api')->id();
        $notification->save();

        return response()->json([
            'status' => 1,
            'message' => 'You have successfully registered.',
            'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
            'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
            'data' => $data,
        ]);
    }

    public function profileView(Request $request)
    {

        $user = User::where('id', auth('api')->id())->first();
        $company_datail = CompanyDetail::where('user_id', auth('api')->id())->first();
        $mailling_address = MaillingAddress::where('user_id', auth('api')->id())->first();

        $data = [
            'user_data' => $user,
            'company_datail' => $company_datail,
            'mailling_address' => $mailling_address,
        ];

        return response()->json([
            'status' => 1,
            'message' => 'User data',
            'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
            'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
            'data' => $data,
        ]);

    }

    public function profileUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . auth('api')->id(),
            'profile_image' => 'mimes:jpg,jpeg,png,svg|max:50000',
            //'sign_image' => 'sometimes|required|string',
            //'secret_pin' => 'sometimes|required|digits_between:4,6|confirmed',
            'company_name' => 'required|string|max:255',
            'job_title' => 'required|string|max:255',
            'street_address' => 'required|string|max:255',
            'apartment_number' => 'sometimes|nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'city_name' => 'required|string|max:255',
            'zip_code' => 'required|digits_between:5,6',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $user = User::where('id', auth('api')->id())->first();
        $company_datail = CompanyDetail::where('user_id', auth('api')->id())->first();
        $mailling_address = MaillingAddress::where('user_id', auth('api')->id())->first();
        if (!empty($user)) {
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->role = 3;
            //$user->secret_pin = Hash::make($request['secret_pin']);
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/user-profile/';
                    $extension = $request->file('profile_image')->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $user->profile_image = $filename;
                }
            }
//            if ($request->has('sign_image')) {
//                $base_64_decoded_image = base64_decode($request['sign_image']);
//                $destinationPath = 'public/storage/uploads/user-sign/';
//                $filename =  uniqid(). "_sign.png";
//                file_put_contents($destinationPath.$filename, $base_64_decoded_image);
//                $user->sign_image = $filename;
//            }
            $user->save();
        }
        if (!empty($company_datail)) {
            $company_datail->user_id = auth('api')->id();
            $company_datail->company_name = $request['company_name'];
            $company_datail->job_title = $request['job_title'];
            $company_datail->save();
        }
        if (!empty($mailling_address)) {
            $mailling_address->user_id = auth('api')->id();
            $mailling_address->street_address = $request['street_address'];
            $mailling_address->apartment_number = $request['apartment_number'];
            $mailling_address->country_id = $request['country_id'];
            $mailling_address->state_id = $request['state_id'];
            $mailling_address->city_name = $request['city_name'];
            $mailling_address->zip_code = $request['zip_code'];
            $mailling_address->save();
        }
        $notification = new Notification();
        $notification->comment = 'Your profile has been updated.';
        $notification->type = 'profile';
        $notification->user_id = auth('api')->id();
        $notification->save();

        $data = [
            'user_data' => $user,
            'company_detail' => $company_datail,
            'mailing_address' => $mailling_address,
        ];
        return response()->json([
            'status' => 1,
            'message' => 'Your profile has been updated.',
            'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
            'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
            'data' => $data,
        ]);
    }

    public function secretPinUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_secret_pin' => 'required',
            'secret_pin' => 'required|digits_between:4,6|confirmed',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $otp = mt_rand(1111, 9999);
        //$otp = 1234;
        $user = User::where('id', auth('api')->id())->first();
        if (!empty($user)) {
            //if (!Hash::check($request['current_secret_pin'], $user['secret_pin'])){
            if ($request['current_secret_pin'] != $user['secret_pin']) {
                return response()->json([
                    "status" => 0,
                    "message" => 'Your current security PIN is incorrect, please try again.',
                    'data' => [],
                ]);
            }
            $user->secret_pin_otp = $otp;
            $user->save();

            twilio_sms($user->country_code, $user->mobile_number, $otp, "goNDA Verification Code: ");

            return response()->json([
                "status" => 1,
                'message' => 'A verification code has been sent to your US phone number.',
                'secret_pin_otp' => $otp,
            ]);
        }
    }

    public function secretPinOtpVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'secret_pin_otp' => 'required',
            'secret_pin' => 'required|digits_between:4,6',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        $user = User::where(['id' => auth('api')->id(), 'secret_pin_otp' => $request['secret_pin_otp']])->first();
        if (!empty($user)) {
            //$user->secret_pin = Hash::make($request['secret_pin']);
            $user->secret_pin = $request['secret_pin'];
            $user->secret_pin_otp = null;
            $user->save();

            $notification = new Notification();
            $notification->comment = 'Your security PIN has been updated.';
            $notification->type = 'secret_pin';
            $notification->user_id = auth('api')->id();
            $notification->save();

            return response()->json([
                "status" => 1,
                "message" => "Your security PIN has been updated.",
                "data" => $user,
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'The entered secret pin otp is invalid.',
            'data' => [],
        ]);
    }

    public function signUpdate(Request $request)
    {
        //return $request['sign_img']['pathName'];
        //return $request->has('sign_img.pathName')? "yes":"no";
        $validator = Validator::make($request->all(), [
            'sign_image' => 'required|string',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $user = User::where('id', auth('api')->id())->first();
            if (!empty($user)) {
                if ($request->has('sign_image')) {
                    $sign = str_replace(' ', '+', $request['sign_image']);
                    $base_64_decoded_image = base64_decode($request['sign_image']);
                    $destinationPath = 'public/storage/uploads/user-sign/';
                    $filename = uniqid() . "_sign.jpg";
                    file_put_contents($destinationPath . $filename, $base_64_decoded_image);
                    $user->sign_image = $filename;
                }
                $user->save();
                $notification = new Notification();
                $notification->comment = "Your set signature has been updated.";
                $notification->type = 'signature';
                $notification->user_id = auth('api')->id();
                $notification->save();
                return response()->json([
                    'status' => 1,
                    'message' => 'Your signature successfully updated.',
                    'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                    'data' => $user,
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
}
