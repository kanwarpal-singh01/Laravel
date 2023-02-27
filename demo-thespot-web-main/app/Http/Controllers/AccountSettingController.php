<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\classes\Account;
use Helper;
use Auth;
use Hash;
use App\User;
use DB;


class AccountSettingController extends Controller
{
    
public function index(){   


$user_id=Auth::id();  
$user=Account::get_user($user_id);

$industries=DB::table('industries')->get();
$documents=DB::table('documents')->where('user_id',$user_id)->get();

return view('front.account_setting.index',compact('user','documents','industries'));

}

public function update(Request $request){


     $validator = $request->validate([
        'old_password' => 'required',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'required| min:6',
     
    ]
 );

 $user=Auth::user();

 if(Hash::check($request->old_password,$user->password)){

$new_password=Hash::make($request->password);
try {
    User::where('id',$user->id)->update(['password'=>$new_password,'industry'=>$request->industry]);
    return redirect()->back()->with('message','Password Updated Sucessfully.');
} catch (Throwable $e) {
    return redirect()->back()->with('error','some problem is there..');
   
}
 }
 else{
return redirect()->back()->with('error','old password  not matched');
 }


}


public function save_account(Request $request){



    $validator = $request->validate([
        'file' => 'max:400',
     
    ]
 );




    try{


        if($request->hasFile('file')) {
             
             $file= $request->file('file');
             $file_name = $file->getClientOriginalName().'.'.$file->getClientOriginalExtension();

             $file_name=time().$file_name;
             $file->move(public_path().'/assets/img/', $file_name);
             User::where('id',Auth::id())->update(['profile_image'=>$file_name]); 
              
         }


        User::where('id',Auth::id())->update(['industry'=>$request->industry]);  
        return redirect()->back()->with('message','Profile Updated Sucessfully.');
    }
    catch(Throwable $e ){
        return redirect()->back()->with('error','something problem is there. Account not updated');

    }
   
}







}