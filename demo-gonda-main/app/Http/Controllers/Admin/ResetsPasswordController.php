<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Model\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetsPasswordController extends Controller
{
    public function resetPasswordView(Request $request, $token){

        return view('admin.password.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request){

        $request->validate([
            'password' => 'required|string|min:6|max:15|confirmed',
            'password_confirmation' => 'required',
        ]);
        $updatePassword = PasswordReset::where('token', $request->token)->first();

        if(!$updatePassword)
        {
            toastr()->error('You can reset your password only one time!');
            return back();
        }
        $user = User::where('email', $updatePassword->email)
                        ->update(['password' => Hash::make($request->password)]);

        PasswordReset::where('token', $request->token)->delete();

        toastr()->success('Your password has been changed successfully!');

        return redirect()->route('admin.login.view');
    }

}
