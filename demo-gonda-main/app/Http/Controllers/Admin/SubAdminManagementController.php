<?php

namespace App\Http\Controllers\Admin;

use App\Model\EmailTemplateManagement;
use App\Http\Controllers\Controller;
use App\Model\Permission;
use App\Model\PermissionModule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class SubAdminManagementController extends Controller
{
    public function list()
    {
        $layout_data = [
            'title' => "Sub Admin Management",
            'url' => route('admin.sub.admin.management.list'),
            'icon' => "fa fa-users",
        ];
        $list = User::with('SubAdminManagement')->where('role', '2')->orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));
        //dd($list);
        return view('admin.sub-admin-management.list', compact('layout_data', 'list'));
    }

    public function add(Request $request)
    {
        $layout_data = [
            'title' => "Sub Admin Management",
            'url' => route('admin.sub.admin.management.list'),
            'icon' => "fa fa-users",
        ];
        $permission_data = Permission::get();
        if ($request->isMethod('post')) {
            $request->validate([
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|unique:users|max:100',
                'mobile_number' => 'required|unique:users|digits_between:7,15',
            ]);
            DB::beginTransaction();
            $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
            $password = substr($random, 0, 10);
            $user = new User();
            $user->first_name = $request['first_name'];
            $user->last_name = $request['last_name'];
            $user->email = $request['email'];
            $user->role = 2;
            $user->mobile_number = $request['mobile_number'];
            $user->password = Hash::make($password);
            $user->updated_by = auth('admin')->user()->id;
            $user->save();
            $module_name = $request['module_name'];
            $module_add = $request['add'] ?? [];
            $module_edit = $request['edit'] ?? [];
            $module_delete = $request['delete'] ?? [];
            $module_view = $request['view'] ?? [];
            if (!empty($module_name)) {
                foreach ($module_name as $permission_id) {
                    $permission = new PermissionModule();
                    $permission->user_id = $user->id;
                    $permission->permission_id = $permission_id;
                    if (in_array($permission_id, $module_add)) {
                        $permission->add = 1;
                    }
                    if (in_array($permission_id, $module_edit)) {
                        $permission->edit = 1;
                    }
                    if (in_array($permission_id, $module_delete)) {
                        $permission->delete = 1;
                    }
                    if (in_array($permission_id, $module_view)) {
                        $permission->view = 1;
                    }
                    $permission->save();
                }
            }

            $mail_array = [
                'first_name' => ucfirst($request['first_name']),
                'last_name' => ucfirst($request['last_name']),
                'email' => $request['email'],
                'password' => $password,
            ];
            $email_template = EmailTemplateManagement::where('slug', 'new_account_create')->first();
            $message = str_replace(['{first_name}', '{last_name}', '{email}', '{password}'], $mail_array, $email_template->content);
            $subject = $email_template->subject;
            $to_name = $mail_array['first_name'] . ' ' . $mail_array['last_name'];
            $to_email = $mail_array['email'];
            $data['msg'] = $message;
            Mail::send('admin.emails.sub-admin-management-email', $data, function ($message) use ($to_name, $subject, $to_email) {
                $message->to($to_email, $to_name)
                    ->subject($subject);
                $message->from(env('MAIL_FROM_ADDRESS'));
            });
            DB::commit();
            toastr()->success('Sub Admin successfully added!');
            return redirect()->route('admin.sub.admin.management.list');
        }
        return view('admin.sub-admin-management.add', compact('layout_data', 'permission_data'));
    }

    public function edit(Request $request, $id, $page = null)
    {
        $layout_data = [
            'title' => "Sub Admin Management",
            'url' => route('admin.sub.admin.management.list'),
            'icon' => "fa fa-users",
        ];
        DB::beginTransaction();
        $permission_data = Permission::get();
        $edit = User::with('permissionModule')->findOrFail(base64_decode($id));
        $permissions = ['permission' => []];
        if ($edit->permissionModule->isNotEmpty()) {
            foreach ($edit->permissionModule as $record) {
                $permissions['permission'][] = $record->permission_id;
                $permissions[$record->permission_id]['add'] = $record->add;
                $permissions[$record->permission_id]['edit'] = $record->edit;
                $permissions[$record->permission_id]['delete'] = $record->delete;
                $permissions[$record->permission_id]['view'] = $record->view;
            }
        }
        foreach ($edit->permissionModule as $record) {
            $permissions['module_name'][] = $record->module_name;
            $permissions[$record->module_name]['add'] = $record->add;
            $permissions[$record->module_name]['edit'] = $record->edit;
            $permissions[$record->module_name]['delete'] = $record->delete;
        }
        if ($request->isMethod('post')) {
            $request->validate([
                'first_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'last_name' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})$/|max:100|unique:users,email,' . base64_decode($id),
                'mobile_number' => 'required|digits_between:7,15|unique:users,mobile_number,' . base64_decode($id),
            ]);
            $edit->first_name = $request['first_name'];
            $edit->last_name = $request['last_name'];
            $edit->email = $request['email'];
            $edit->mobile_number = $request['mobile_number'];
            $module_name = $request['module_name'];
            $module_add = $request['add'] ?? [];
            $module_edit = $request['edit'] ?? [];
            $module_delete = $request['delete'] ?? [];
            $module_view = $request['view'] ?? [];
            if (!empty($module_name)) {
                PermissionModule::whereNotIn('permission_id', $module_name)->where('user_id', $edit->id)->delete();
                foreach ($module_name as $permission_id) {
                    $permission = PermissionModule::where([
                        'user_id' => $edit->id,
                        'permission_id' => $permission_id
                    ])->first();

                    if (empty($permission))
                        $permission = new PermissionModule();

                    $permission->user_id = $edit->id;
                    $permission->permission_id = $permission_id;

                    if (in_array($permission_id, $module_add)) {
                        $permission->add = 1;
                    } else {
                        $permission->add = 0;
                    }
                    if (in_array($permission_id, $module_edit)) {
                        $permission->edit = 1;
                    } else {
                        $permission->edit = 0;
                    }
                    if (in_array($permission_id, $module_delete)) {
                        $permission->delete = 1;
                    } else {
                        $permission->delete = 0;
                    }
                    if (in_array($permission_id, $module_view)) {
                        $permission->view = 1;
                    } else {
                        $permission->view = 0;
                    }
                    $permission->save();
                }
            } else {
                $permission = PermissionModule::where('user_id', $edit->id)->get();
                $permission->each->delete();
            }
            $edit->save();
            DB::commit();
            toastr()->success('Sub Admin successfully updated!');
            return redirect('admin/sub-admin-management/list?page=' . $page);
        }
        return view('admin.sub-admin-management.edit', compact('layout_data', 'edit', 'permissions', 'permission_data'));
    }

    public function delete(Request $request, $id)
    {

        $delete = User::findOrFail(base64_decode($id));

        if ($delete->status === 1) {
            $delete->status = 0;
            toastr()->success('Sub Admin successfully deleted!');
        } else {
            $delete->status = 1;
            toastr()->success('Sub Admin successfully active!');
        }
        $delete->save();
        return redirect()->back();
    }

    public function view(Request $request, $id)
    {
        $layout_data = [
            'title' => "Sub Admin Management",
            'url' => route('admin.sub.admin.management.list'),
            'icon' => "fa fa-users",
        ];
        $view = User::with('permissionModule.permission')->findOrFail(base64_decode($id));
        return view('admin.sub-admin-management.view', compact('layout_data', 'view'));
    }
}
