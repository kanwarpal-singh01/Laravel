<?php

namespace App\Http\Controllers\Admin;

use App\Model\EmailTemplateManagement;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function list()
    {
        $layout_data = [
            'title' => "User Management",
            'url' => route('admin.user.list'),
            'icon' => "fa fa-users",
        ];
        $list = User::with('SubAdminManagement', 'document')->where('role', '3')->orderBy('created_at', 'asc')->paginate(config('custom-paginate.paginate.number'));
        //dd($list);
        return view('admin.user-management.list', compact('layout_data', 'list'));
    }

    public function add(Request $request)
    {
        $layout_data = [
            'title' => "User Management",
            'url' => route('admin.user.list'),
            'icon' => "fa fa-users",
        ];
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|unique:users|max:100',
                'mobile_number' => 'required|unique:users|digits_between:7,15',
                'password' => 'required|string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|min:6|max:15|confirmed',
                'password_confirmation' => 'required',
                'status' => 'required|in:0,1',
            ], [
                'password.regex' => 'Password should have at least one Uppercase, one lowercase, one numeric and one special character.'
            ]);
            DB::beginTransaction();
            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->role = 3;
            $user->mobile_number = $request->mobile_number;
            $user->password = Hash::make($request->password);
            $user->updated_by = auth('admin')->user()->id;
            $user->save();
            $mail_array = [
                'first_name' => ucfirst($request['first_name']),
                'last_name' => ucfirst($request['last_name']),
                'email' => $request['email'],
                'password' => $request['password'],
            ];
            $email_template = EmailTemplateManagement::where('slug', 'new_account_create')->first();

            $message = str_replace(['{first_name}', '{last_name}', '{email}', '{password}'], $mail_array, $email_template->content);
            $subject = $email_template->subject;
            $to_name = $mail_array['first_name'] . " " . $mail_array['last_name'];
            $to_email = $mail_array['email'];
            $from_email = env('MAIL_FROM_ADDRESS', 'yallacashdubai@gmail.com');
            $data['msg'] = $message;

            Mail::send('admin.emails.user', $data, function ($message) use ($to_name, $subject, $to_email, $from_email) {
                $message->to($to_email, $to_name)
                    ->subject($subject)
                    ->from($from_email);
            });
            DB::commit();
            toastr()->success('User successfully added!');
            return redirect()->route('admin.user.list');
        }
        return view('admin.user-management.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page = null)
    {
        $layout_data = [
            'title' => "User Management",
            'url' => route('admin.user.list'),
            'icon' => "fa fa-users",
        ];
        $edit = User::findOrFail(base64_decode($id));
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:100',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/|max:100|unique:users,email,' . base64_decode($id),
                'mobile_number' => 'required|digits_between:7,15|unique:users,mobile_number,' . base64_decode($id),
                'password' => 'sometimes|nullable|string|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|min:6|max:15|confirmed',
                'status' => 'required|in:0,1',

            ], [
                'password.regex' => 'Password should have at least one Uppercase, one lowercase, one numeric and one special character.'
            ]);
            $edit->first_name = $request['first_name'];
            $edit->last_name = $request['last_name'];
            $edit->email = $request['email'];
            $edit->mobile_number = $request['mobile_number'];
            $edit->status = $request['status'];
            if (!empty($request['password'])) {
                $edit->password = Hash::make($request['password']);
            }
            $edit->save();
            DB::commit();
            toastr()->success('User successfully updated!');
            return redirect('Kobe/user-management/list?page=' . $page);
        }
        return view('admin.user-management.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id)
    {
        $delete = User::findOrFail(base64_decode($id));
        if ($delete) {
            $delete->delete();
            toastr()->success('User successfully deleted!');
            return redirect()->back();
        } else {
            toastr()->error('Something went wrong!');
            return redirect()->back();
        }
    }

    public function view(Request $request, $id)
    {
        $layout_data = [
            'title' => "User Management",
            'url' => route('admin.user.list'),
            'icon' => "fa fa-users",
        ];
        $view = User::with('companyDetail', 'mailingAddress.countries', 'mailingAddress.states')->findOrFail(base64_decode($id));
        return view('admin.user-management.view', compact('view', 'layout_data'));
    }
}
