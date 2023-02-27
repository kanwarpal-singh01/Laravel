<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request){

        if ($request->isMethod('post')){
             $request->validate([
                 'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|exists:users|max:100',
                 'password' => 'required|string|min:6|max:15',
             ]);
            $credentials = $request->only('email', 'password');
            if (Auth::guard('front_end_user')->attempt($credentials, $request->remember))
            {
                toastr()->success('Welcome to Home!');
                return redirect()->route('frontend.home');
            }
            toastr()->error('Invalid credentials!');
            return redirect()->route('frontend.login');
        }
        return view('front-end.login');
    }

    public function logout()
    {
        Auth::guard('front_end_user')->logout();
        return redirect()->route('frontend.login');
    }
}
