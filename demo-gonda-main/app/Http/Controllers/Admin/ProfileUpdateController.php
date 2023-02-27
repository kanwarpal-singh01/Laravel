<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateController extends Controller
{
    public function profileUpdateView(Request $request){
        $layout_data = [
            'title' => 'Profile Update',
            'url' => route('admin.profile.update.view'),
            'icon' => 'fa fa-user',
        ];
        return view('admin.profile-update', compact('layout_data'));
    }

    public function profileUpdate(Request $request){
        $request->validate([
            'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
            'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
            'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/|max:100',
            'profile_image' => 'mimes:jpg,jpeg,png|max:50000',
        ]);
        $user = Auth::user();
        if ($user){
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/users/';
                    $extension = $request->file('profile_image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $user->profile_image = $filename;
                }
            }
            $user->save();
            toastr()->success('Profile successfully updated!');
            return redirect()->back();
        }
        toastr()->error('Profile not updated!');
        return redirect()->back();
    }
}
