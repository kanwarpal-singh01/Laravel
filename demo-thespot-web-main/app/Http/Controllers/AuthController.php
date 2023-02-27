<?php

namespace App\Http\Controllers;
use Illuminate\Mail\Mailer;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Mail\RegistrationMail;
use App\Mail\ForgetPassword;
use Hash;
use Validator;
use Auth;
use App\User;
use App\Models\EmailTemplate;
use Cart;
use Helper;
//use Session;

class AuthController extends Controller
{
/**
* Create a new controller instance.
*
* @return void

*/

public function __construct()
{



}

//INDEX PAGE
public function index()
{
   return view('welcome');
}
public function admin_login(Request $request){
   if(Auth::check()){
      $user = Auth::user();
      if($user->status == '1' && $user->role == 'admin'){
         return redirect()->route('admin_dashboard');
      }else{
         return view('admin.login');
      }
   }
   /* Login After submit */
   if($request->isMethod('post')){

      //$validator = Validator::make($request->all(),['email' => 'required|email|max:255','password' => 'required|min:5']);
      $this->validate($request, [
         'email' => 'required|max:255',
         'password' => 'required|min:5'
      ]);
      $remember = $request->has('remember')? true: false;

      if(auth()->attempt(array('email' => $request->input('email'),'password' =>$request->input('password')), $remember)){
         if($remember){
            setcookie('email',$request->input('email'), time()+(86400*30),"/");
            setcookie('password',$request->input('password'), time()+(86400*30),"/");
         }else{  
            setcookie('email',$request->input('email'), time()-(86400*30),"/");
            setcookie('password',$request->input('password'), time()-(86400*30),"/");
         }
         $user = Auth::user();
         if($user->status == 1 && $user->role == 'admin'){
            return redirect()->route('admin_dashboard');
         }
         Auth::logout();
         return redirect()->back()->with('error','You are unauthorised user');
      }else{
         return redirect()->back()->with('error', 'Email or Password in incorrect.');
      }
   }else{
      return view('admin.login');
   }
}
public function admin_forget_password(){
   return view('forgot_password');
}


//END USER LOGIN VIEW
public function login(Request $request){

   if(Auth::check()){

      $user = Auth::user();

      if($user->status == 1 && $user->role == 'employer'){
         return redirect('account-setting');

      }elseif($user->status == 1 && $user->role == 'recruiter'){
         return redirect('account-setting');
      }else{
         return view('front/main.login');
      }
   }
   return view('front/main.login');
}

//FRONT USER LOGIN
public function auth(Request $req){

   $this->validate($req, [
      'email' => 'required|max:255',
      'password' => 'required',
   ]);

   $remember = $req->has('remember')? true: false;
   if(auth()->attempt(array('email' => $req->input('email'),'password' =>$req->input('password')), $remember)){



      if($remember)
      {
         setcookie('email',$req->input('email'), time()+(86400*30),"/");
         setcookie('password',$req->input('password'), time()+(86400*30),"/");
      } else { 

         setcookie('email',$req->input('email'), time()-(86400*30),"/");
         setcookie('password',$req->input('password'), time()-(86400*30),"/");
      }
      $employer_credit_status=Helper::employer_credit_status();


      $user = Auth::user();
      $user->fcm_token=$req->fcm;
      if(!$employer_credit_status){
         $user->plan=0;
      }

      $user->save();
      $id = $user->id;
      if($user->status == 1 && ($user->role == 'recruiter' || $user->role == 'sub-recruiter')){
         return redirect('account-setting');
      }elseif($user->status == 1 && $user->role == 'employer')
      {     

         return redirect('account-setting');
      }elseif($user->email_confirmed == 0 && ($user->role == 'employer' || $user->role == 'recruiter')){


         $emailtemplate = EmailTemplate::where('slug','user-registration')->first();
         $activationlink = url('/activate/').'/'.base64_encode($user->id);

         $emailtemplate->content = str_replace(array('{name}','{url}'),array($user->name,$activationlink), $emailtemplate->content);

         $fromemail = config("Settings.EmailFrom");
         $email  = $user->email;

         Auth::logout();
         return redirect()->back()->with('error','Your account not activated please click resend email <a href="resend-email/'.base64_encode($user->id).'">Resend Mail</a>');

      }elseif($user->status == 0 && ($user->role == 'employer' || $user->role == 'recruiter' || $user->role == 'sub-recruiter')){

         Auth::logout();
         return redirect()->back()->with('error','Your profile is under verification, we will inform you as profile verification completed.');
      }else{

         Auth::logout();
         return redirect()->back()->with('invalid','You are unauthorised user');
      }

   }else{


      return redirect()->back()->with('both_invalid','Email and password are wrong');
   }            
}

//FORNT USER LOGOUT
public function logout(){
   $user = Auth::user();
   Auth::logout();
   Session::flush();
   return redirect()->route('login');
}

//ADMIN LOGOUT
public function admin_logout(){
   Auth::logout();
   Session::flush();
   return redirect('admin/login');
}

//eND USER REGISTER
public function register(Request $request, $role=null){    


   if(Auth::check())
   {
      return redirect('dashboard');
   }

   if($role != null && ($role == 'employer' || $role == 'recruiter')){


      if($request->method() == 'POST')
      {

         if($role=='employer'){
            $this->validate($request, [
               'user_name' => 'required',
               'firm_name' => 'required',
               'phone' => 'required|max:15',
               'company_description'=>'required | max:5000',
               'email' => 'required|email|unique:users|max:255',
               'password' => 'required|min:6',
               'password_confirm' => 'required|same:password',
               'files' => 'required' ,
               'terms'=>'required'   
            ]);
         }
         else{
            $this->validate($request, [
               'user_name' => 'required',
               'agency_name' => 'required',
               'phone' => 'required|max:15',
               'agency_description'=>'required | max:5000',
               'email' => 'required|email|unique:users|max:255',
               'password' => 'required|min:6',
               'password_confirm' => 'required|same:password',
               'files' => 'required' ,
               'terms'=>'required'   
            ]);
         }




         $user = DB::table('users')->where('email',$request->email)->where('status','!=','2')->first();
         if($user){
            Session::flash('error','Email already registered');
            return redirect()->to('register'.'/'.$role)->with('error','Email Already Registered.');
         }
         $input =  $request->all();
         $input['role'] = $role;

         $plan=DB::table('plans')->where('role',$role)->where('slug','basic')->first();

         $input['credits'] =  $plan->credit;
         $plan_expire_date=date('Y/m/d', strtotime(date('Y/m/d').' + 30 days'));
         if($role=='employer'){
            $input['plan'] = 1;
            $input['plan_expire_date']= $plan_expire_date;
            $input['job_limit'] =  $plan->job_limit;
         }
         else{
            $input['plan'] = 5;
            $input['plan_expire_date']= $plan_expire_date;
            $input['cv_limit'] =  $plan->cv_limit;
            $input['sub_recruiter_limit'] =  $plan->sub_recruiter_limit;

         }





         $input['profile_image'] = 'default_user.png';
         $input['password'] = Hash::make($input['password']);


         $register = User::create($input);
         DB::table('credits_history')->insert(['user_id'=> $register->id,'credits'=>10, 'payment_for'=>'credit']);

         if($request->hasFile('files')) {


            foreach($request->file('files') as $file){


               $file_name = Helper::move_file($file,$role,'doc');
               Helper::update_file_info($register->id,$file_name,'doc');

            }

         }



         if(isset($register->id) && !empty($register->id))
         {
            $emailtemplate = EmailTemplate::where('slug','user-registration')->first();
            $activationlink = url('/activate/').'/'.base64_encode($register->id);
            if(isset($emailtemplate->content) && !empty($emailtemplate->content)){
               $emailtemplate->content = str_replace(array('{name}','{url}'),array($input['user_name'],$activationlink), $emailtemplate->content);

               Mail::to($register->email)->send(new RegistrationMail($emailtemplate->content));

               $stripe = new \Stripe\StripeClient(Config('app.stripe_secret') );
               $customer=$stripe->customers->create([
                  'description' => $register->user_name,
               ]);

               DB::table('stripe_customers')->insert(['user_id'=> $register->id,'customer_id'=>$customer->id]);

// Session::flash('message', 'You are registered successfully');
               return view('front.main.check_email');
            }

            Session::flash('error', 'Internal server error');
            return redirect()->to('register'.'/'.$role);

         }
         Session::flash('error', 'Internal server error');
         return redirect()->to('register'.'/'.$role);
      }

      if($role=='employer'){
         return view('front/main.signup');
      }
      else{
         return view('front/main.signup_recruiter');
      }

   }
   return route('login');
}


//END USER EMAIL VERIFICATION
public function activate($id)
{
   $id =  base64_decode($id);

   $data = User::find($id);
//$data = User::get($id);
   if($data->status == 0)
   {

      $data->email_confirmed = 1;
      $data->save();
      Session::flash('message', 'Your account activated');
      return view('front.main.activation_success');
   }
   elseif($data->status == 1)
   {

      Session::flash('warning','Activation link expired');

      return redirect()->route('login');
   }
   else
   {
      Session::flash('error', 'Activation link expired');
      return redirect()->to('/');
   }
}

//FRONT END USE FORGET PASSWORD VIEW
public function forget_password(){
   return view('front/main.forget_password');
}

//ADMIN FORGET PASSWORD VIEW


//FRONT END USE SEND FORGET LINK MAIL
public function send_password_request(Request $request){
   $this->validate($request,[
      'email' => 'required|email'
   ]);
   $email = $request->input('email');
   $matchText = ['email'=>$email,'status'=>'1','role'=>'admin'];
   $data = User::where($matchText)->first();
   $fromemail = config('Settings.EmailFrom');
   if($data){
      $reset_token = mt_rand(111111,999999);
      $templateData = EmailTemplate::where([['slug', 'forgot-password'],['status', 1]])->first();
      $link = url('/reset_password').'/'.base64_encode($reset_token);
      date_default_timezone_set('Asia/Kolkata');
      DB::table('users')->where(['email'=>$email])->update(['reset_password_token'=>$reset_token, 'created_at'=>date('Y-m-d H:i:s')]);

      $mailMessage = str_replace(array('{username}','{resetlink}'), array($data->name,$link), $templateData->content);           
      Mail::to($email)->send(new ForgetPassword($mailMessage));

      Session::flash('message','Password Reset Mail sent successfully');
      return redirect()->intended('forget_password');

   }else{
      Session::flash('invalid', 'Invalid Email');
      return redirect()->intended('forget_password');            
   }
}


// ADMIN USE SEND FORGET LINK MAIL
public function admin_send_password_request(Request $request){
   $this->validate($request,[
      'email' => 'required|email'
   ],[

      'email.required' =>__('validation.required',['attribute' => __('validation.attribute.email')]),
      'email.email' => __('validation.email',['attribute'=> __('validation.attribute.email')]),
   ]);
   $email = $request->input('email');
   $matchText = ['email'=>$email,'status'=>'1','role'=>'admin'];
   $data = User::where($matchText)->first();
   if($data){
      $reset_token = mt_rand(111111,999999);
      $templateData = EmailTemplate::where(['slug'=>'forgot-password','status'=>'1'])->first();
      //pr($templateData);die;
      $link = url('admin/reset_password').'/'.base64_encode($reset_token);
      date_default_timezone_set('Asia/Kolkata');
      DB::table('users')->where(['email'=>$email])->update(['reset_password_token'=>$reset_token, 'created_at'=>date('Y-m-d H:i:s')]);
      $templateData->content = str_replace(array('{username}','{resetlink}'), array($data->name,$link), $templateData->content);
      Mail::to($email)->send(new ForgetPassword($templateData->content));
      Session::flash('message', 'Reset Link sent successfully on your mail Id');    
      return redirect()->intended('admin/login');

   }else{
      Session::flash('error', 'Invalid Email');    
      return redirect()->intended('admin/forget_password');
   }
}

//FRONT USER RESET PASSWORD 
public function reset_password($token){
   $data = User::where('reset_password_token', base64_decode($token))->first(['id','email','reset_password_token','created_at']);
   date_default_timezone_set('Asia/Kolkata');
   if($data && strtotime("+10 minutes",strtotime($data->created_at)) > strtotime(date("Y-m-d H:i:s")))
//if($data)
   {
      return view('front/main.reset_password', array('id' => base64_encode($data->id),'title' => "The spot || Reset Password"));
   }
   else
   {
      Session::flash('error', 'Link has been expired'); 
      return redirect()->intended('/forget_password');
   }
}

//ADMIN RESET PASSWORD
public function admin_reset_password($token){
   $data = User::where('reset_password_token', base64_decode($token))->first(['id','email','reset_password_token','created_at']);
   date_default_timezone_set('Asia/Kolkata');
   if($data && strtotime("+10 minutes",strtotime($data->created_at)) > strtotime(date("Y-m-d H:i:s")))
   {
      return view('reset_password', array('id' => $data->id,'title' => "The Spot || Reset Password"));
   }
   else
   {
      Session::flash('error', 'Link has been expired'); 
      return redirect()->intended('admin/forget_password');
   }
}

//ADMIN UPDATE PASSWORD
public function update_password(Request $request){

   $this->validate($request,array(
      'password' => 'required|min:5|confirmed',
      'password_confirmation' => 'required|min:5'));
   $user = User::where('id',$request->id)->first();
   if($user){
      $user->password = Hash::make($request->password);
      $user->reset_password_token = null;
      $user->save();

      if($user->role == 'admin'){
         Session::flash('message', 'Password reset Successfully.');
         return redirect('/admin/login');
      }else{

         Session::flash('message', 'Password Reset Sucessfully');
         return redirect()->route('login');
      }            
   }else{

      if($user->role == 'admin'){
         Session::flash('error', 'Internal server error');
         return redirect()->intended('admin/forget_password');
      }else{
         Session::flash('error', 'Internal server error');
         return redirect()->intended('/forget_password');
      }
   }
}

public function resendEmail($id){

   $user = User::Where('id',base64_decode($id))->first();
   $emailtemplate = EmailTemplate::where('slug','user-registration')->first();
   $activationlink = url('/activate/').'/'.base64_encode($user->id);

   if(isset($emailtemplate->content) && $emailtemplate->content != "")
   {

      $emailtemplate->content = str_replace(array('{name}','{url}'),array($user->name,$activationlink), $emailtemplate->content);


   }
   else
   {

      $emailtemplate->content = str_replace(array('{name}','{url}'),array($user->name,$activationlink), $emailtemplate->content);
   }

   $fromemail = config("app.email_from");
   $email  = $user->email;
   Mail::send('emails.registration',['user'=>$emailtemplate->content ], function ($m) use ($fromemail,$email,$emailtemplate){
      $m->from($fromemail, 'Bagoo');
      $m->to($email)->subject($emailtemplate->subject);
   });

   return view('front.main.check_email');

}
//FRONT END USE HOME
public function home(){
   return view('front/main.home');
}
}
