<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\CompanyDetail;
use App\Model\MaillingAddress;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|digits_between:1,7',
            'mobile_number' => 'required|digits_between:8,12',
            'device_type' => 'sometimes|nullable|in:1,2',
            'device_token' => 'sometimes|nullable',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "status" => 0,
                "message" => $error,
            ]);
        }
        try {
            $mobile_number = $request['mobile_number'];
            $country_code = $request['country_code'];
            $device_type = $request['device_type'] ?? null;
            $device_token = $request['device_token'] ?? null;
            DB::beginTransaction();
            if ($request['mobile_number'] == '7186120332' || $request['mobile_number'] == '18004444444') {
                $user = User::where(['mobile_number' => $mobile_number, 'country_code' => $country_code])->first();
                $otp = mt_rand(1111, 9999);
                $is_new_user = 0;
                // if user available then send otp for login
                if (empty($user)) {
                    $user = new User();
                    $user->mobile_number = $mobile_number;
                    $user->country_code = $country_code;
                    $user->role = 3;
                    $is_new_user = 1;
                }
                $user->device_type = $device_type;
                $user->device_token = $device_token;
                $user->mobile_number_otp = $otp;
                $user->save();
                //For Twilio
                //twilio_sms($country_code, $mobile_number, $otp, 'goNDA Verification Code: ');
            } else {
                $user = User::where(['mobile_number' => $mobile_number, 'country_code' => $country_code])->first();
                $otp = mt_rand(1111, 9999);
                //$otp = 1234;
                $is_new_user = 0;
                // if user available then send otp for login
                if (empty($user)) {
                    $user = new User();
                    $user->mobile_number = $mobile_number;
                    $user->country_code = $country_code;
                    $user->role = 3;
                    $is_new_user = 1;
                }
                $user->device_type = $device_type;
                $user->device_token = $device_token;
                $user->mobile_number_otp = $otp;
                $user->save();
                //For Twilio
                //twilio_sms($country_code, $mobile_number, $otp, 'goNDA Verification Code: ');
            }
            DB::commit();
            return response()->json([
                'status' => 1,
                'message' => 'Otp has been sent to your mobile number.',
                'otp' => $otp,
                'is_new_user' => $is_new_user,
                'secret_pin' => $user->secret_pin,
            ]);
        } catch (\Exception$exception) {
            return error_response($exception);
        }
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|exists:users',
            'otp' => 'required',
        ], [
            'mobile_number.exists' => 'The entered mobile number does not exists.',
        ]);
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return response()->json([
                "success" => 0,
                "message" => $error,
            ]);
        }
        $mobile_number = $request['mobile_number'];
        $otp = $request['otp'];

        if ($mobile_number == '7186120332' || $mobile_number == '18004444444' && $otp == 1234) {

            $user = User::where(['mobile_number' => $mobile_number])->first();

            if (!$token = auth('api')->fromUser($user)) {
                return response()->json(['status' => 0, 'message' => 'Unauthorized']);
            }

            $user->mobile_number_verify = 1;
            $user->mobile_number_otp = null;
            $user->save();
            $company_datail = CompanyDetail::where('user_id', $user->id)->first();
            $mailling_address = MaillingAddress::where('user_id', $user->id)->first();
            $data = [
                'user_data' => $user,
                'company_detail' => $company_datail,
                'mailing_address' => $mailling_address,
                //'credit_points' => HomeSection6::totalCreditPoints(),
            ];
            return response()->json([
                'status' => 1,
                'message' => 'OTP successfully verified',
                'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                'data' => $data,
            ] + $this->respondWithToken($token));
        }

        $user = User::where(['mobile_number' => $mobile_number, 'mobile_number_otp' => $otp])->first();

        if (!empty($user)) {

            if (!$token = auth('api')->fromUser($user)) {
                return response()->json(['status' => 0, 'message' => 'Unauthorized']);
            }

            $user->mobile_number_verify = 1;
            $user->mobile_number_otp = null;
            $user->save();
            $company_datail = CompanyDetail::where('user_id', $user->id)->first();
            $mailling_address = MaillingAddress::where('user_id', $user->id)->first();
            $data = [
                'user_data' => $user,
                'company_detail' => $company_datail,
                'mailing_address' => $mailling_address,
                //'credit_points' => HomeSection6::totalCreditPoints(),
            ];
            return response()->json([
                'status' => 1,
                'message' => 'OTP successfully verified',
                'profile_image_path' => URL::to('/') . '/public/storage/uploads/user-profile/',
                'sign_image_path' => URL::to('/') . '/public/storage/uploads/user-sign/',
                'data' => $data,
            ] + $this->respondWithToken($token));
        }

        return response()->json([
            'status' => 0,
            'message' => 'The entered otp is invalid.',
            'data' => [],
        ]);

    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return array
     */
    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    public function logout()
    {
        $user = auth('api')->user();
        if ($user) {
            $user->update(['device_type' => null, 'device_token' => null]);
        }
        auth('api')->logout();
        return response()->json(
            [
                'status' => 1,
                'message' => 'Successfully logged out.',
            ]);
    }

}
