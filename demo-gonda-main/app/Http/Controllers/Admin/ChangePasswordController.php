<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function changePasswordView(){
        $layout_data = [
            'title' => "Change Password",
            'url' => route('admin.change.password.view'),
            'icon' => 'fa fa-unlock-alt'
        ];
        return view('admin.password.change-password', compact('layout_data'));
    }
    public function changePassword(Request $request){
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|max:15|confirmed',
            'password_confirmation' => 'required',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)){
            toastr()->error('Current password does not match!');
            return redirect()->route('admin.change.password.view');
        }
        $user->password = Hash::make($request->password);
        $user->save();
        toastr()->success('Password successfully changed!');
        return redirect()->route('admin.change.password.view');
    }
}
