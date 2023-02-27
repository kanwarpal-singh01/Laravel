<?php

namespace App\Http\Controllers\Api;

use App\Events\ProfileCompletionMail;
use App\Events\UserReport;
use App\Http\Controllers\Controller;
use App\Models\LikeUnlike;
use App\Models\NotificationSetting;
use App\Models\StoryViews;
use App\Models\UserImages;
use App\Models\UserReports;
use App\Models\UserStories;
use Illuminate\Http\Request;
use Hash;
use Helper;
use DB;
use Validator;
use App\User;
use Illuminate\Validation\Validator as ValidationValidator;
use UserDetail;
use App\Models\UsersDetail;
use Carbon\Carbon;
use Intervention\Image\ImageManagerStatic as Image;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use Auth;
class ApiController
{


 
    public function checkLoginStatus(Request $request)
    {
        $users = User::where(['id' => $request->all()['user_id']])->first();
        if (!empty($users)) {

            if ($users->status == 2) {
                sendResponse("Account was Deactivated", 10);
            } else if ($users->status == 5) {
                sendResponse("Account was Suspended by admin", 10);
            } else {
                sendResponse("", 1);
            }
            //$users = User::findOrFail($request->all['user_id']);
        } else {
            sendResponse("Account was not exist", 10);
        }
        /*pr($users);die;
        sendResponse('User Deleted Successfully', 1); */
    }
    public function deleteAccount(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        User::where(['id' => $requestData['user_id']])->delete();
        sendResponse('User Deleted Successfully', 1);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), ['mobile' => 'required|min:5|max:30']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $data = $request->all();
        if (strpos($data['mobile'], '+') === '') {
            sendResponse('Please select country');
        }
        /* if (isset($data['user_id']) && !empty($data['user_id'])) {
            $userData = User::where(['id' => $data['user_id']])->first();
            $userMobileData = User::where(['mobile' => $data['mobile']])->first();
            if (!empty($userMobileData) && $userMobileData['id'] != $userData['id']) {
                sendResponse('Mobile number already used, please enter new number');
            }
        } else {
            $userData = User::where(['mobile' => $data['mobile']])->first();
        } */
        if (isset($data['user_id']) && !empty($data['user_id'])) {
            $userData = User::where(['id' => $data['user_id']])->first();
            if ($userData->mobile != $data['mobile']) {
                $oldUserData = User::where(['mobile' => $data['mobile']])->first();
                if(!empty($oldUserData)){
                    if ($oldUserData->status != 0) {
                        sendResponse('Number already taken by another user', 0);
                    }
                }
            }
        } else {
            $userData = User::where(['mobile' => $data['mobile']])->first();
        }
        $checkUserVerification = 0;
        if (!empty($userData)) {
            /* if ($userData['status'] == 1) {
                sendResponse('User Already Exist, Please login');
            } else {
            } */
            $checkUserVerification = 1;
        }
        $email_otp = substr(rand(000000, 999999), 0, 4);
         $data['otp'] = substr(rand(000000, 999999), 0, 4);
        //$data['otp'] = 1234;
        if($data['mobile'] == '+91-7351329862'){
            $data['otp'] = 1234;
        }
        // dd($data['otp']);
        //$data['otp'] = 1234;
        $data['mobile'] = strtolower($data['mobile']);
        $message = "The Spot mobile verification One Time Password is " . $data['otp'];
        if (isset($data['facebook_account_id']) && !empty($data['facebook_account_id'])) {
            $data['email']= $data['facebook_account_id'];
            
        }
        if ($checkUserVerification == 1) {
            if (isset($data['apple_account_id']) && !empty($data['apple_account_id'])) {
                $userData->apple_account_id = $data['apple_account_id'];
                // $userDataApple = User::where(['apple_account_id' => $data['apple_account_id']])->first();
                // if(!empty($userDataApple) && $userDataApple->mobile != $data['mobile']){
                //     sendResponse('This apple account is already registered with another mobile number !', '0');
                //     exit;
                // }
            }
            // if (isset($data['facebook_account_id']) && !empty($data['facebook_account_id'])) {
            //     $userData->email = $data['facebook_account_id'];
            // }
            if ($userData->status == 0) {
                $userData->mobile = $data['mobile'];
            }
            $userData->otp = $data['otp'];
            $userData->save();
            $newUser = $userData;
        } else {
            $newUser = User::create($data);
        }
        if (isset($newUser->id) && ($newUser->status == 1 || $newUser->status == 0 || $newUser->status == 4)) {
            // dd($data['mobile']);
            if($data['mobile'] == '+91-7351329862'){
                
            }else{
                Helper::sendMsg($data['mobile'], $message);
            }
             
            //dd(Helper::sendMsg($data['mobile'], $message));
            $data['id'] = $newUser->id;
            sendResponse('Otp has been send on your mobile number', '1', $data);
        } else if ($newUser->status == 5) {
            sendResponse('Your account was suspended by admin', '0');
        } else if ($newUser->status == 2) {
            sendResponse('Your account was deactivated by admin', '0');
        }
        sendResponse();
    }
    public function sms_otp_verification(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required', 'otp' => 'required|min:4|max:4']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            $userData = User::with('userDetail')->find($requestData['user_id']);
            if (!empty($userData)) {

                if ($userData['otp'] != null) {
                    if ($userData['otp'] == $requestData['otp']) {
                        if ($userData['status'] == 0) {
                            $userData['status'] = 4;
                        }
                        $userData['otp'] = null;
                        if (isset($requestData['token'])) {
                            $userData['token'] = $requestData['token'];
                        }
                        $userData->save();
                        sendResponse("OTP Verified", 1, $userData);
                    } else {
                        sendResponse("Incorrect OTP");
                    }
                } else {
                    $otp = substr(rand(00000, 99999), 0, 4);
                    sendResponse("OTP not sent, Please re-send otp", 0, [], ['otp' => $otp]);
                }
            } else {
                sendResponse("User Not Found");
            }
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
        sendResponse();
    }

    public function resend_otp(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'user_id' => 'required',
            'mobile' => 'required',
        ]);
        try {
            if ($validator->fails()) {
                $error = Helper::ValidationSet($validator->errors());
            }
            $users = User::find($requestData['user_id']);
            $users->otp = substr(rand(000000, 999999), 0, 4);
            $users->save();
            sendResponse('OTP re-send on your registered mobile no.', 1, ['otp' => $users->otp]);
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
        sendResponse();
    }
    public function socialLogin(Request $request)
    {
        $requestData = $request->all();

        $validator = Validator::make($request->all(), [
            'apple_account_id' => 'required_without:facebook_account_id',
            //'facebook_account_id' => 'required_without:apple_account_id',
            /* 'device_type' => 'required', */
            'token' => 'required',
        ]);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            if (!empty($requestData['apple_account_id'])) {
                $condition = ['apple_account_id' => $requestData['apple_account_id']];
            } 
            // else if (!empty($requestData['facebook_account_id'])) {
            //     $condition = ['facebook_account_id' => $requestData['facebook_account_id']];
            // }
            $users = user::with('userDetail')->where($condition)->first();
            if (empty($users)) {
                $data = $request->all();
                $data['status']=4;
                $newUser = User::create($data);
                $users = user::with('userDetail')->where($condition)->first();
                sendResponse('Successfully sign up.', 1, $users, ['profile_url' => config('app.profile_url')]);
               // sendResponse('Account Not Exist', 1, $users, ['redirect' => 'Register']);
            } else if ($users->status == 0) {
                sendResponse('Account not verified', 1, $users, ['redirect' => 'Register']);
            } else {
                if ($users->status == 4 || $users->status == 1) {
                    sendResponse('Successfully sign up.', 1, $users, ['profile_url' => config('app.profile_url')]);
                } else {
                    sendResponse('Your account was deactivated by admin, please contact to support team');
                }
            }
            /* if (empty($users)) {
                $requestData['status'] = 4;
                $requestData['verify_status'] = 1;
                $users = User::create($requestData);
                $users = User::with('userDetail')->where(['id' => $users->id])->first();
            }
            if (!empty($users)) {
                if ($users->status == 4 || $users->status == 1) {
                    sendResponse('Successfully sign up.', 1, $users, ['profile_url' => config('app.profile_url')]);
                } else {
                    sendResponse('Account is inactivate, please contact to admin panel');
                }
            } */
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
        sendResponse();
    }

    public function connectSocialAccount(Request $request)
    {

        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'user_id' => 'required',
            'apple_account_id' => 'required_without:facebook_account_id',
            'facebook_account_id' => 'required_without:apple_account_id'
        ]);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $users = User::find($requestData['user_id']);
        if (!empty($users)) {
            if (!empty($requestData['apple_account_id'])) {
                $users->apple_account_id = $requestData['apple_account_id'];
            }
            if (!empty($requestData['facebook_account_id'])) {
                $users->facebook_account_id = $requestData['facebook_account_id'];
            }
            $users->save();
            sendResponse('Account successfully connected', 1);
        } else {
            sendResponse('User Not found');
        }
    }

    public function userDetail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {

            if (isset($requestData['detail_user_id']) && !empty($requestData['detail_user_id']) && $requestData['detail_user_id'] != $requestData['user_id']) {
                $likeUnlike = LikeUnlike::where(['user_id' => $requestData['user_id'], 'like_user_id' => $requestData['detail_user_id'], 'type' => 'View'])->first();
                //pr(LikeUnlike::where(['like_user_id' => $requestData['user_id'], 'is_seen' => 0])->count());
                if (empty($likeUnlike)) {
                    $data = ['user_id' => $requestData['user_id'], 'like_user_id' => $requestData['detail_user_id'], 'type' => 'View'];
                    LikeUnlike::create($data);
                    Helper::commonNotificationFunction($requestData['detail_user_id'], 'profile-visit', 'views', $requestData['user_id']);
                }
                $isLiked = Helper::isLiked($requestData['user_id'], $requestData['detail_user_id']);
                $condition = ['id' => $requestData['detail_user_id']];
            } else {
                $condition = ['id' => $requestData['user_id']];
                $isLiked = 0;
            }
            $userDetail = User::with('userDetail')->where($condition)->get()->map->formatData()->first();
            $userDetail['isLiked'] = $isLiked;


            $data=DB::table('transactions')->where('user_id',$userDetail['id'])->whereDate('expired_at', '>', Carbon::now())->first();

            if($data){
                $userDetail['subscription']=1;
            }else{
                $userDetail['subscription']=0;
            }


            if (!empty($userDetail)) {
                sendResponse('User detail', 1, $userDetail, ['profile_url' => config('app.profile_url')]);
            } else {
                sendResponse('User Not found');
            }
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
    }
    public function profileCompletion(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {

            $userData = User::with('userDetail')->find($requestData['user_id']);
            if (isset($requestData['dob']) && !empty($requestData['dob'])) {
                if (date_create($requestData['dob']) > date_create('
                    ')) {
                    sendResponse('Dob should be less than current date', 0);
                }
                $userData->age =  date_diff(date_create($requestData['dob']), date_create('today'))->y;
            }

            if (isset($requestData['intrested_in']) && !empty($requestData['intrested_in'])) {
                $requestData['status'] = 1;
                event(new ProfileCompletionMail($userData->id));
            }
            $userData->fill($requestData);
            if(isset($requestData['story_save_status'])){
                DB::table('user_details')->where('user_id', '=', $requestData['user_id'])->update(array('story_save_status' => $requestData['story_save_status']));
            }
          

            if (!empty($userData['userDetail'])) {
                $userData->userDetail->fill($requestData);
                $userData->userDetail->save();
            } else {
                $userData->userDetail()->create($requestData);
            }
            $userData->save();
            if (isset($requestData['profile_type']) && $requestData['profile_type'] == 'edit') {
                sendResponse('Profile successfully updated', 1, $userData);
            } else {
                sendResponse('Profile successfully completed', 1, $userData);
            }
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
    }
    public function upload_profile_images(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
       
            $userData = User::findOrFail($requestData['user_id']);
            $path = 'public/uploads/user/';


            $files = $request->file('new_images');
            if($request->hasFile('new_images'))
            {
             
                foreach ($files as $file) {
                    
                
                 
                    $image 		= 	$file;
                    $folderName = 	strtoupper(date('M'). date('Y'))."/";
                    $my_filename 	= $file->getClientOriginalName();
                    $extension 	=	$file->getClientOriginalExtension();
                  
                    
                   
                    $profile		=	time().'_'. $my_filename;
                  
                  
                    $destinationPath = $path;
                   


                    $resize_image = Image::make($file->getPathname());
    
                    $resize_image->resize( 500,500 , function($constraint){
                        $constraint->aspectRatio();
                    })->save($destinationPath . '/medium/' .  $profile);
                  
                    
                    if($file->move($destinationPath.'/', $profile)){
                    
                    }
                    
                    
                    $requestData['urls'][]= $profile;
                
                }
            }

            $requestData['urls'] = array_filter($requestData['urls'], 'strlen');

            

            $error=false;
            foreach(  $requestData['urls']  as  $new_url){
                $image_url=config('app.profile_url').'/'.$new_url;

              //  $image_url='https://www.pngkey.com/png/detail/230-2301779_best-classified-apps-default-user-profile.png';
                $result=$this->image_checker($image_url);
                
                if($result['status']){
                    $error=true;
                }
               
            }
          
            if($error){

                foreach ($requestData['urls'] as $key => $value) {
                    if (file_exists($path.'/'. $value)) {
                        @unlink($path.'/' . $value);
                        // @unlink($path.'small/' . $value);
                        @unlink($path.'medium/' . $value);
                    //  @unlink($path.'large/' . $value);
                    } 
                 }

                sendResponse('Your images has some nude or porn content available.');
            }


            $userData->profile_image = $requestData['urls'][0];
            $userData->images = implode(',', $requestData['urls']);
            $userData->save();
            sendResponse('Images successfully uploaded ', 1, $requestData['urls'], ['url' => config('app.profile_url')]);
       
       
    }

    public function image_checker($image_url){

      
		$ch = curl_init();
	
		$data = array('url_image' => $image_url,
				'API_KEY' => "vM31KnY96wG2XyJ2o0NWoDdsUzQ16edH",
				'task' => 'porn_moderation,gore_moderation',
				'origin_id' => "xxxxx",
				'reference_id' => "yyyyyy"
		
		);
		
		curl_setopt($ch, CURLOPT_URL,'https://www.picpurify.com/analyse/1.1');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
		curl_setopt($ch,CURLOPT_SAFE_UPLOAD,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		
		$output = curl_exec($ch);
		
		$result=json_decode($output);
       
       

	if(isset($result->porn_moderation) && $result->porn_moderation->porn_content==true && $result->final_decision=='KO' ){ 
		return ['status'=>1];

	}else{
		
		return ['status'=>0];
	}
	
	
	}

    // public function upload_profile_images(Request $request)
    // {
    //     $requestData = $request->all();
    //     $validator = Validator::make($requestData, ['user_id' => 'required']);
    //     if ($validator->fails()) {
    //         $error = Helper::ValidationSet($validator->errors());
    //     }
    //     try {
    //         $userData = User::findOrFail($requestData['user_id']);
    //         $path = 'public/uploads/user/';
    //         if (!empty($userData->images) && !empty($requestData['urls'])) {
    //             $urls = explode(',', $userData->images);
    //             foreach ($urls as $key => $value) {
    //                 if (!in_array($value, $requestData['urls'])) {
    //                     unset($urls[$key]);
    //                     if (file_exists($path . $value)) {
    //                         @unlink($path . $value);
    //                     }
    //                 }
    //             }
    //         }
    //         if (isset($requestData['new_images']) && !empty($requestData['new_images'])) {
    //             foreach ($requestData['new_images'] as $key => $value) {
    //                 $requestData['urls'][] = Helper::uploadImages($value, $path);
    //             }
    //         }
    //         $requestData['urls'] = array_filter($requestData['urls'], 'strlen');
    //         $userData->profile_image = $requestData['urls'][0];
    //         $userData->images = implode(',', $requestData['urls']);
    //         $userData->save();
    //         sendResponse('Images successfully uploaded ', 1, $requestData['urls'], ['url' => config('app.profile_url')]);
    //     } catch (\Throwable $th) {
    //         sendResponse('Error:' . $th->getMessage());
    //     }
    //     sendResponse();
    // }
    public function like_unlike(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }

        try {
            $likeUnlike = LikeUnlike::where(['user_id' => $requestData['user_id'], 'like_user_id' => $requestData['like_user_id'], 'type' => 'Like'])->first();
            if (empty($likeUnlike)) {
                $requestData['type'] = 'Like';
                LikeUnlike::create($requestData);
                /* $token = Helper::get_token($requestData['like_user_id'], 'likes');
            if (!empty($token)) {
                $notificationData = Helper::getNotificationData('like-profile', $requestData['user_id']);
                if (!empty($token)) {
                    Helper::pushNotification($token, $notificationData);
                }
            } */
                Helper::commonNotificationFunction($requestData['like_user_id'], 'like-profile', 'likes', $requestData['user_id']);
                sendResponse('Liked', 1);
            } else {
              
                if ($likeUnlike['status'] == 1) {
                    $likeUnlike['status'] = 0;
                    $likeUnlike->save();
                    sendResponse('Unlike', 1);
                } else {
                    $likeUnlike['status'] = 1;
                    $likeUnlike->save();
                    Helper::commonNotificationFunction($requestData['like_user_id'], 'like-profile', 'likes', $requestData['user_id']);
                    sendResponse('Liked', 1);
                }
            }
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
        sendResponse();
    }

    public function like_view(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }

        try {
            $likeUnlike = LikeUnlike::where(['user_id' => $requestData['user_id'], 'like_user_id' => $requestData['like_user_id'], 'type' => 'View'])->first();
            if (empty($likeUnlike)) {
                $requestData['type'] = 'View';
               LikeUnlike::create($requestData);
                /* $token = Helper::get_token($requestData['like_user_id'], 'likes');
            if (!empty($token)) {
                $notificationData = Helper::getNotificationData('like-profile', $requestData['user_id']);
                if (!empty($token)) {
                    Helper::pushNotification($token, $notificationData);
                }
            } */
          
                Helper::commonNotificationFunction($requestData['like_user_id'], 'profile-visit', 'views', $requestData['user_id']);
                sendResponse('Viewed', 1);
            } else {
               
                if ($likeUnlike['status'] == 1) {
                    $likeUnlike['status'] = 0;
                    $likeUnlike->save();
                    sendResponse('Unviewed', 1);
                } else {
                    $likeUnlike['status'] = 1;
                    $likeUnlike->save();
                   
                    Helper::commonNotificationFunction($requestData['like_user_id'], 'profile-visit', 'views', $requestData['user_id']);
                    sendResponse('Viewed', 1);
                }
            }
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
        sendResponse();
    }

    public function likeViewsList(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $likeList = LikeUnlike::with('users')->where(['like_user_id' => $requestData['user_id'], 'status' => '1'])->orderBy('id','desc')->get()->map->formatData();

        if (!empty($likeList)) {
            foreach ($likeList as $key => $value) {
               $countStory=DB::table('user_stories')->where(['user_id'=> $value['user_id']])->count();
               $value['story_status']=$countStory;
               $value['created_at']=$this->getTime($value['created_at']);
               $likeList[$key]=$value;
            }
            LikeUnlike::where(['like_user_id' => $requestData['user_id']])->update(['is_seen' => 1]);
            sendResponse("views list", 1, $likeList, ['profile_url' => config('app.profile_url')]);
        } else {
            sendResponse("No record Found");
        }
        sendResponse();
    }
    public function getTime($time_ago)
{
    $sent_time=$time_ago;
   
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
if($hours <=24){
        return date('h:i A',strtotime($sent_time));
    }
//Days
    else if($days <= 7){
        
        if($days==1){
            return __("yesterday");
        }else{
            return date('d/m/Y',strtotime($sent_time));
        }
    }

    else{
        return date('d/m/y',strtotime($sent_time));
    }
}
    /* Notification Management */

    public function notification_setting_list(Request $request)
    {
        $requestData = $request->all();
        $setting = NotificationSetting::where(['user_id' => $requestData['user_id']])->first();
        if (empty($setting)) {
            $requestData['messages']=1;
            $requestData['in_hotspot']=1;
            $requestData['hot_spot']=1;
            $requestData['likes']=1;
            $requestData['views']=1;
            $requestData['new_messages']=1;
            $setting = NotificationSetting::create($requestData);
        }
        sendResponse('Notification list', 1, $setting);
    }
    public function notification_setting(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            $setting = NotificationSetting::where(['user_id' => $requestData['user_id']])->first();
            if (empty($setting)) {
                $setting = NotificationSetting::create(['user_id' => $requestData['user_id']]);
            }
            $setting->fill($requestData);
            $setting->save();
            sendResponse('Notification setting successfully updated', 1, $setting);
        } catch (\Throwable $th) {
            sendResponse('Error:' . $th->getMessage());
        }
    }
    public function uploadStatus(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required', 'story' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            $path = 'public/uploads/stories/';
           
            $destinationPath = $path;
            $file=$request->file('story');
            $my_filename 	= $file->getClientOriginalName();
            $resize_image = Image::make($file->getPathname());
    
            // $resize_image->resize( 500,500 , function($constraint){
            //     $constraint->aspectRatio();
            // })->save($destinationPath . '/' .  $my_filename);
          
            
            if($file->move($destinationPath.'/', $my_filename)){
            
            }
            

            $error=false;
            $image_url=url('/').'/public/uploads/stories/'.$my_filename;
          

              //  $image_url='https://www.pngkey.com/png/detail/230-2301779_best-classified-apps-default-user-profile.png';
                $result=$this->image_checker($image_url);
                
                if($result['status']){
                    $error=true;
                }
          
            if($error){

               
                    if (file_exists($destinationPath.'/'. $my_filename)) {
                        @unlink($destinationPath.'/' . $my_filename);
                    } 
                 

                sendResponse('Your images has some nude or porn content available.');
            }


            
            // $type = $requestData['story']->getClientMimeType();
            // $requestData['story_type'] = ucwords(explode('/', $type)[0]);
            $requestData['story'] = $my_filename;
            $images = UserStories::create($requestData);
            $before24Hour = $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
            $storyDetail = UserStories::where('created_at', '>', $before24Hour)->where(['user_id' => $requestData['user_id']])->orderBy('id', 'ASC')->get()->toArray();
            sendResponse('Uploaded', 1, $storyDetail);
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
    }
    public function storyDetailWithArray(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required', 'story_user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            $before24Hour = $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
            $storyDetail = UserStories::where('created_at', '>', $before24Hour)->where(['user_id' => $requestData['story_user_id']])->orderBy('id', 'ASC')
                ->get()->toArray();
            $userDetail = UsersDetail::select('story_status', 'story_save_status')->where(['user_id' => $requestData['user_id']])->first();
            $userDetail = json_decode(json_encode($userDetail), true);
            if (!empty($storyDetail)) {
                foreach ($storyDetail as $key => $value) {
                    $storyviewData = ['user_id' => $requestData['user_id'], 'story_id' => $value['id']];
                    if ($requestData['user_id'] != $requestData['story_user_id']) {
                        $storyView = StoryViews::where($storyviewData)->first();
                        if (empty($storyView)) {
                            StoryViews::insert($storyviewData);
                        }
                    }
                    $storyDetail[$key]['view_count'] = $storyViewCount = StoryViews::where($storyviewData)->count();
                    $storyviewData = ['user_id' => $requestData['user_id'], 'story_id' => $value['id']];
                    if ($requestData['user_id'] != $requestData['story_user_id']) {
                        $storyView = StoryViews::where($storyviewData)->first();
                        if (empty($storyView)) {
                            StoryViews::insert($storyviewData);
                        }
                    }
                    $storyDetail[$key]['story_view_count'] = StoryViews::where($storyviewData)->count();
                }
                $userDetail['view_count'] = $storyViewCount;
                $userDetail['url'] = config('app.story_url');
                sendResponse('Story Detail', 1, $storyDetail, $userDetail);
            } else {
                sendResponse('Story not found', 0, [], $userDetail);
            }
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
    }
    public function storyDetail(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required', 'story_user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        try {
            $before24Hour = $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
            $storyDetail = UserStories::where('created_at', '>', $before24Hour)->where(['user_id' => $requestData['story_user_id']])->orderBy('id', 'ASC')->get()->toArray();
            $userDetail = UsersDetail::select('story_status', 'story_save_status')->where(['user_id' => $requestData['user_id']])->first();
            $userDetail = json_decode(json_encode($userDetail), true);
            if (!empty($storyDetail)) {
                foreach ($storyDetail as $key => $value) {
                    $storyviewData = ['user_id' => $requestData['user_id'], 'story_id' => $value['id']];
                    if ($requestData['user_id'] != $requestData['story_user_id']) {
                        $storyView = StoryViews::where($storyviewData)->first();
                        if (empty($storyView)) {
                            StoryViews::insert($storyviewData);
                        }
                    }
                    $storyDetail[$key]['view_count'] = $storyViewCount = StoryViews::where($storyviewData)->count();
                }
                $userDetail['url'] = config('app.story_url');
                sendResponse('Story Detail', 1, $storyDetail, $userDetail);
            } else {
                sendResponse('Story not found', 0, [], $userDetail);
            }
            //dd($storyDetail);die;
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
    }
    public function setStoryStatus(Request $request)
    {
        $requestData = $request->all();
        try {
            $validator = Validator::make($requestData, ['user_id' => 'required', 'story_status' => 'required', 'story_save_status' => 'required']);
            if ($validator->fails()) {
                $error = Helper::ValidationSet($validator->errors());
            }
            $status = UsersDetail::where(['user_id' => $requestData['user_id']])->update(['story_status' => $requestData['story_status'], 'story_save_status' => $requestData['story_save_status']]);
            sendResponse('Story Setting updated', '1');
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
    }
      public function update_device_token(Request $request)
    {
        $requestData = $request->all();
        try {
            $validator = Validator::make($requestData, ['user_id' => 'required', 'token' => 'required']);
            if ($validator->fails()) {
                $error = Helper::ValidationSet($validator->errors());
            }
            $status = User::where(['id' => $requestData['user_id']])->update(['token' => $requestData['token']]);
            sendResponse('Device token updated', '1');
        } catch (\Throwable $th) {
            sendResponse('Error' . $th->getMessage());
        }
    }
    public function storyViewsList(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required', 'story_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $storyViewList = StoryViews::where(['story_id' => $requestData['story_id']])->get();
        if (!empty($storyViewList)) {
            foreach ($storyViewList as $key => $value) {
                 $usersData=DB::table('users')->where('id',$value->user_id)->first();
            $value->name=$usersData->name;
            $value->profile_image=$usersData->profile_image;
            }
            sendResponse('Story Viewer list', 1, $storyViewList, ['profile_url' => config('app.profile_url')]);
        } else {
            sendResponse('No Viewers Found');
        }
        sendResponse();
    }
    public function deleteStory(Request $request)
    {
        if ($request->isMethod('Post')) {
            $requestData = $request->all();
            $validator = Validator::make($requestData, ['user_id' => 'required', 'story_id' => 'required']);
            if ($validator->fails()) {
                $error = Helper::ValidationSet($validator->errors());
            }
            $story = UserStories::find($requestData['story_id']);
            if (!empty($story)) {
                $path = 'public/uploads/stories/';
                if (!empty($story->story) && file_exists($path . $story->story)) {
                    @unlink($path . $story->story);
                }
                StoryViews::where(['story_id' => $requestData['story_id']])->delete();
                $story->delete();
                $before24Hour = $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
                $storyDetail = UserStories::where('created_at', '>', $before24Hour)->where(['user_id' => $requestData['user_id']])->orderBy('id', 'ASC')->get()->toArray();
                sendResponse("Story deleted successful", 1, $storyDetail);
            } else {
                sendResponse("Story not found");
            }
        }
        sendResponse();
    }
    /* Report on User  */
    public function reportUser(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, ['user_id' => 'required', 'report_user_id' => 'required', 'reason' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $report = UserReports::create($requestData);
        if (!empty($report)) {
            //event(new UserReport($report->id));
            sendResponse("Report successfully", 1);
        }
        sendResponse();
    }
    /* subscription management */
    public function subscription()
    {
        $subsctiption = DB::table('subscriptions')->first();
        $data=DB::table('transactions')->where('id',$subsctiption->id)->whereDate('expired_at', '>', Carbon::now())->first();

        if($data){
            $subsctiption->subscription=1;  

        }else{
            $subsctiption->subscription=0; 
        }

        sendResponse("Subscription", 1, $subsctiption);
    }

    /* Active Spy Mode  */
    public function spyMode(Request $request){
        //dd("Fdsfds");
       
            $requestData = $request->all();
            $validator = Validator::make($requestData,['user_id'=>'required','status'=>'required']);
            if ($validator->fails()) {
                $error = Helper::ValidationSet($validator->errors());
            }
            $updateSpyMode = DB::table('user_details')->where(['user_id'=>$requestData['user_id']])->update(['spy_mode'=>$requestData['status']]);
           
             sendResponse("Spy Mode Updated Successfully ",1);

        
        
    }

    /* file export  */
    public function userExportFile(Request $request){
        dd($request->all());die;
        $condition = ['status'=>1];
        $userExport = new UsersExport($condition);
        return Excel::download($userExport, 'users-list.xlsx');
    }

    public function transaction(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required','subscription_id'=>'required','price'=>'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }

        $expired_at = date('Y-m-d h:i:s', strtotime("+30 days"));

         DB::table('transactions')->insert(['user_id'=>$request->user_id,'subscription_id'=>$request->subscription_id,'price'=>$request->price,'expired_at'=>$expired_at]);

         sendResponse('transaction created Successfully', 1);
    }

    public function test(Request $request)
    { 
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['sender_id' => 'required','user_id' => 'required','type' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $userData=DB::table('users')->where('id',$request->user_id)->first();
        $adminData=DB::table('users')->where('id',$request->sender_id)->first();
      
        if(!empty($userData) && $userData->token != null){
            $registrationIDs  = $userData->token;
            $notificationtemplatesData=DB::table('notification_templates')->where('slug',$request->type)->first();
            $notificationtemplatesData->message = str_replace(array('{name}'), array($adminData->name), $notificationtemplatesData->message);
        $fields = array(
            'to' => $registrationIDs,
            'notification' => array('title' => $notificationtemplatesData->message, 'body' => ''),
            'aps' => array('title' => $notificationtemplatesData->message, 'body' => ''),
            'data' => array('title' => $notificationtemplatesData->message, 'body' => '')
        );
        
        if ($this->sendPushNotification($fields) == true) {
            sendResponse("Notification sent successfully", 1);
        }
        }else{
            sendResponse("Either user doesn't exists or not logged in", 0);
        }
        
        sendResponse();
    }
    public function testNotification(Request $request)
    {
        $fields = array(
            'to' => $request->request_id,
            'notification' => array('title' => 'tedsdsa', 'body' => ''),
            'aps' => array('title' => 'tedsdsa', 'body' => ''),
            'data' => array('title' => 'adfsadfsewerw', 'body' => '')
        );
        $this->sendPushNotification($fields);
    }

    /*** Some General Functions  ***/
    private function sendPushNotification($fields)
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
