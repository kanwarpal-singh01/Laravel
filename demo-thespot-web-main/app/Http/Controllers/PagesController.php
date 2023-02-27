<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use View;
use App\User;
use Helper;
use Carbon\Carbon;
use App\Models\UserStories;
class PagesController extends Controller
{
    public function privacy_policy(){
        $pageData = DB::table('cms_pages')->where(['slug'=>'privacy-policy'])->first();
        //view('pages/cms_pages', compact('pageData'))->render();
        return View::make('pages.cms_pages')->with(compact('pageData'));
    }
     public function getMapUser($user_id,$lat,$lng){
      
        $userData = User::with('userDetail')->find($user_id);
        DB::table('notification')->where('user_id', '=', $user_id)->delete();
        if($userData->drop_pin_status == 1){
            $lat = $userData->lat;
            $lng = $userData->lng;
        }else{
            $userData->update(['lat' => $lat, 'lng' => $lng]);
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
        
        // $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."' order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$user_id." and deleted_at IS NUll ";
        $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$user_id." and deleted_at IS NUll ";

        if($userData->userDetail->incognito_mode == 0){
            $where = " where (users.id = ".$user_id. ") OR ( incognito_mode = 0 and ". $gender." and age BETWEEN ". $age_group[0]." and ". $age_group[1]. " ) and deleted_at IS NUll ";
        }else {
            $where = "where users.id = ".$user_id. "  and deleted_at IS NUll ";
        }
        $where .= " and spy_mode = 0  ";

        // $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."'  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$lng.")) + sin(radians(".$lat.")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$user_id."  ORDER BY distance ";
        $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$lng.")) + sin(radians(".$lat.")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$user_id."  ORDER BY distance ";

        $data = DB::select($sql);
        $data = json_decode(json_encode($data),true);
        $radiusData = $loginUserData = [];
        foreach ($data as $key => $value) {
            // dd($value);
            if(!empty($value['story'])){
                $value['story'] = Helper::checkStoryAccess($value['story'], $user_id);
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

            if($value['id'] == $user_id){
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
     
        // $extraData = ['login_user_data'=> $loginUserData,'profile_url'=>config('app.profile_url').'medium/','story_url'=>config('app.story_url')];
       
        return View::make('pages.user_map')->with(compact('user_id','lat','lng','radiusData','loginUserData'));;
    }
    public function drop_map($user_id,$lat,$lng){
      
        $userData = User::with('userDetail')->find($user_id);
        DB::table('notification')->where('user_id', '=', $user_id)->delete();
        if($userData->drop_pin_status == 1){
            $lat = $userData->lat;
            $lng = $userData->lng;
        }else{
            $userData->update(['lat' => $lat, 'lng' => $lng]);
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
        
        // $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."' order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$user_id." and deleted_at IS NUll ";
        $sql = "SELECT (select story from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name  FROM users inner join user_details on (user_details.user_id = users.id) where  users.id = ".$user_id." and deleted_at IS NUll ";

        if($userData->userDetail->incognito_mode == 0){
            $where = " where (users.id = ".$user_id. ") OR ( incognito_mode = 0 and ". $gender." and age BETWEEN ". $age_group[0]." and ". $age_group[1]. " ) and deleted_at IS NUll ";
        }else {
            $where = "where users.id = ".$user_id. "  and deleted_at IS NUll ";
        }
        $where .= " and spy_mode = 0  ";

        // $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id and user_stories.created_at > '".$before24Hour."'  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$lng.")) + sin(radians(".$lat.")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$user_id."  ORDER BY distance ";
        $sql = "SELECT (select id from user_stories where user_stories.user_id = users.id  order by created_at desc limit 1) as story,age,users.id,users.lat,users.lng,users.profile_image,name, ( 6371 * acos( cos( radians(".$lat.") ) * cos( radians( users.lat ) ) * cos( radians(users.lng) - radians(".$lng.")) + sin(radians(".$lat.")) * sin( radians(users.lat)))) AS distance  FROM users inner join user_details on (user_details.user_id = users.id) ".$where." HAVING distance < 1000 or users.id = ".$user_id."  ORDER BY distance ";

        $data = DB::select($sql);
        $data = json_decode(json_encode($data),true);
        $radiusData = $loginUserData = [];
        foreach ($data as $key => $value) {
            // dd($value);
            if(!empty($value['story'])){
                $value['story'] = Helper::checkStoryAccess($value['story'], $user_id);
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

            if($value['id'] == $user_id){
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
     
        // $extraData = ['login_user_data'=> $loginUserData,'profile_url'=>config('app.profile_url').'medium/','story_url'=>config('app.story_url')];
       
        return View::make('pages.drop_map')->with(compact('user_id','lat','lng','radiusData','loginUserData'));;
    }
    public function terms_and_conditions(){
        $pageData = DB::table('cms_pages')->where(['slug'=>'terms-and-conditions'])->first();
        return View::make('pages.cms_pages')->with(compact('pageData'));
    }
    public function about_us(){
        $pageData = DB::table('cms_pages')->where(['slug'=>'about-us'])->first();
        return View::make('pages.cms_pages')->with(compact('pageData'));
    }
    public function contact_us(){
        $pageData = DB::table('cms_pages')->where(['slug'=>'contact-us'])->first();
        return View::make('pages.cms_pages')->with(compact('pageData'));
    }

    public function getNamebyId($user_id="dasd"){
         $user = User::find($user_id);
        echo $user->name;
        exit();
        
    }
    public function readChat()
    {
         //$updateSpyMode = DB::table('chats')->update(['is_read'=>1]);
         echo "Done";
    }
     public function updateChatStatus(Request $request)
    {
        $updateSpyMode = DB::table('user_chats')->where(['room'=>$request->room])->update(['chat_status'=>'Accept']);
         echo "Done";
    }
    public function deleteImage(Request $request)
    {
         $userData = User::with('userDetail')->findOrFail($request->user_id);
         $profileImages = explode(',', $userData->images); 
        
           if(count($profileImages)==1){
             $input['profile_image']='no_image.jpg';
         }
         unset($profileImages[$request->image_id]);

       
         $input['images'] = implode(',', $profileImages);
       
         //$update=DB::table('users')->where('id',$request->user_id)->update(['images'=>$profileImages]);
          $userData->fill($input)->save();
        echo "Done";
    }
}
