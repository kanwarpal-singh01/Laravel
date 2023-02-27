<?php

namespace App\Http\Controllers\Admin;

use App\Model\EmailTemplateManagement;
use App\Http\Controllers\Controller;

use App\Model\PasswordReset;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    public function forgetPasswordView(){
        return view('admin.password.forget-password');
    }

    public function forgetPassword(Request $request){
        $this->validate($request,[
            'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|exists:users|max:100',
        ],
        [
            'email.exists' => 'The entered email address does not exists.'
        ]);
        $token = Str::random(60);
        PasswordReset::create([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
        $link = "<a href='".url('admin/reset-password/'.$token)."'> Click hear to reset password</a>";
        $email_template = EmailTemplateManagement::where('slug', 'forget_password')->first();
        $message = str_replace("{link}", $link, $email_template->content);
        $subject = $email_template->subject;
        $to_email = $request->email;
        $data['msg'] = $message;
        Mail::send('admin.emails.forget-password-link', $data, function ($message) use ($subject, $to_email) {
            $message->to($to_email)
                ->subject($subject);
            $message->from(env('MAIL_FROM_ADDRESS'));
        });
        toastr()->success('We have e-mailed your password reset link!');
        return redirect()->route('admin.forget.password.view');
    }
}
