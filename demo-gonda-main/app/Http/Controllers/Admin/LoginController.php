<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }

    public function loginView()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|exists:users|max:100',
            'password' => 'required|string|min:6|max:15',
        ],
        [
            'email.exists' => 'The entered email address does not exists.'
        ]);
        $data = User::where('email', $request->email)->first();
        $credentials = $request->only('email', 'password');
        if ($data->status === 1) {
            if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
                toastr()->success('Welcome to dashboard!');
                return redirect()->route('admin.dashboard');
            }
            toastr()->error('Invalid credentials!');
            return redirect()->route('admin.login.view');
        }
        toastr()->error('Your account is deleted please contact to Admin!');
        return redirect()->route('admin.login.view');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login.view');
    }
}
