<?php

namespace App\Http\Controllers\Admin;

use App\Model\EmailTemplateManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailTemplateManagementController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => "Email Template Management",
            'url' => route('admin.email.template.management.list'),
            'icon' => "fa fa-envelope"
        ];
        $list = EmailTemplateManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.email-template-management.list', compact('layout_data', 'list'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => "Email Template Management",
            'url' => route('admin.email.template.management.list'),
            'icon' => "fa fa-envelope"
        ];
        $edit = EmailTemplateManagement::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|max:255',
                'subject' => 'sometimes|nullable|max:255',
                'content' => 'sometimes|nullable|string'
            ]);
            $edit->title = $request['title'];
            $edit->subject = $request['subject'];
            $edit->content = $request['content'];
            $edit->save();
            toastr()->success('Email successfully updated!');
            return redirect('Kobe/email-template-management/list?page='.$page);
        }
        return view('admin.email-template-management.edit', compact('layout_data', 'edit'));
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => "Email Template Management",
            'url' => route('admin.email.template.management.list'),
            'icon' => "fa fa-envelope"
        ];
        $view = EmailTemplateManagement::findOrFail(base64_decode($id));
        return view('admin.email-template-management.view', compact('layout_data','view'));
    }
}
