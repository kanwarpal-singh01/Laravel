<?php

namespace App\Http\Controllers\Api;

use App\Events\ProfileCompletionMail;
use App\User;
use App\Http\Controllers\Controller;
use App\Models\LikeUnlike;
use Illuminate\Http\Request;
use Validator;
use Helper;
use DB;
use Event;
use Dotenv\Validator as DotenvValidator;
use Carbon\Carbon;
use App\Models\UserStories;
class DashboardController
{
    public function dashboard(Request $request){
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $userData = User::with('userDetail')->find($requestData['user_id']);
        DB::table('notification')->where('user_id', '=', $requestData['user_id'])->delete();
        if($userData->drop_pin_status == 1){
            $requestData['lat'] = $userData->lat;
            $requestData['lng'] = $userData->lng;
        }else{
            $userData->update(['lat' => $requestData['lat'], 'lng' => $requestData['lng']]);
        }
       
        $intrested_in = (isset($userData->userDetail->intrested_in ) && !empty($userData->userDetail->intrested_in))? $userData->userDetail->intrested_in:'';
        if($intrested_in == 'Both'){
            $gender = '(gender = "Male" Or gender = "Female")';
        }else{
            $gender = 'gender = "'.$intrested_in.'" ';
        }
        $age_group = (isset($userData->userDetail->age_group ) && !empty($userData->userDetail->age_group))? $userData->userDetail->age_group:'18-100';
        $age_group = explode('-', $age_group);
        $before24Hour = $date = date("Y-m-d H:i:s", strtotime('-24 hours', time()));
        $storyDetail = UserStories::where('created_at', '<=', $before24Hour)->delete();
        
        // $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."' order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$requestData['user_id']." and deleted_at IS NUll ";
        $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$requestData['user_id']." and deleted_at IS NUll ";

        if($userData->userDetail->incognito_mode == 0){
            $where = " where (users.id = ".$requestData['user_id']. ") OR ( incognito_mode = 0 and ". $gender." and age BETWEEN ". $age_group[0]." and ". $age_group[1]. " ) and deleted_at IS NUll ";
        }else {
            $where = "where users.id = ".$requestData['user_id']. "  and deleted_at IS NUll ";
        }
        $where .= " and spy_mode = 0  ";

        // $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."'  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$requestData['lat'].") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$requestData['lng'].")) + sin(radians(".$requestData['lat'].")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$requestData['user_id']."  ORDER BY distance ";
        $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$requestData['lat'].") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$requestData['lng'].")) + sin(radians(".$requestData['lat'].")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$requestData['user_id']."  ORDER BY distance ";

        $data = DB::select($sql);
        $data = json_decode(json_encode($data),true);
        $radiusData = $loginUserData = [];
        foreach ($data as $key => $value) {
            // dd($value);
            if(!empty($value['story'])){
                $value['story'] = Helper::checkStoryAccess($value['story'], $requestData['user_id']);
            }
            $meterDistance = round($value['distance']*1000,2);
            $value['radius_status'] = 0;
            if($meterDistance < 500){
                $value['radius_status'] = 1;
            }
            $meterDistance = round($meterDistance);
            $distance = round($value['distance']);
            
            if($value['distance'] >= 1){
                $value['distance_show_value'] = $distance.' km';
            }else{
                $value['distance_show_value'] = $meterDistance.' m';
            }
            $value['distance'] = $distance;
            $value['distance_key'] = $value['distance']*1000;

            if($value['id'] == $requestData['user_id']){
                $loginUserData = $value;
                //$data = json_decode(json_encode($data),true);
            }else{

               $userDataValue=DB::table('users')->where(['id'=> $value['id']])->first();

             
                  if($userDataValue->status != 5){

                      $radiusData[] = $value;
                    
                }
                  
            }

        }
       
         $userDetData = User::with('userDetail')->find($userData['id']);
         $transactionData=DB::table('transactions')->where('user_id',$userData['id'])->whereDate('expired_at', '>', Carbon::now())->first();

         if($transactionData){
             $loginUserData['subscription']=1;
         }else{
             $loginUserData['subscription']=0;
         }
        $loginUserData['drop_pin_status'] = $userData->drop_pin_status;
         $loginUserData['spy_mode'] = $userDetData->userDetail->spy_mode;
         $loginUserData['story_save_status'] = $userDetData->userDetail->story_save_status;
        $loginUserData['like_view_count'] = LikeUnlike::where(['like_user_id'=>$userData->id,'status'=>1,'is_seen'=>0])->count();
        $extraData = ['login_user_data'=> $loginUserData,'profile_url'=>config('app.profile_url').'medium/','story_url'=>config('app.story_url')];
        
        if(!empty($radiusData)){
            sendResponse('dashboard',1, $radiusData,$extraData);
        }else{
            sendResponse('No Data Found',1,[], $extraData);
        }
        sendResponse();
    }
    public function getLoginUserData(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $userData = User::with('userDetail')->find($requestData['user_id']);
    
        if(!empty($userData)){
            $loginUserData['lat'] = $userData->lat;
            $loginUserData['lng'] = $userData->lng;
            sendResponse('Get Location',1, $loginUserData);
        }else{
            sendResponse('No Data Found',0);
        }
    }
    public function updateLocation (Request $request){
        $requestData = $request->all();
        $validator = Validator::make($request->all(), ['user_id' => 'required','lat'=>'required','lng'=>'required']);
        if ($validator->fails()) {
            $error = Helper::ValidationSet($validator->errors());
        }
        $userData = User::with('userDetail')->find($requestData['user_id']);
        if(!isset($requestData['drop_pin_status'])){
            $requestData['drop_pin_status'] = 0;
        }
        $userData->update(['lat' => $requestData['lat'], 'lng' => $requestData['lng'], 'drop_pin_status'=>$requestData['drop_pin_status']]);
        sendResponse('Location Update',1);
    }
}