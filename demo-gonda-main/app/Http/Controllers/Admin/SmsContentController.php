<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\SmsContent;
use Illuminate\Http\Request;

class SmsContentController extends Controller
{
    public function list(){
        $layout_data= [
            'title' => 'SMS Content Management',
            'url' => route('admin.sms.content.list'),
            'icon' => 'fa fa-file'
        ];
        $list = SmsContent::paginate(config('custom-paginate.paginate.number'));
        return view('admin.sms-content.list', compact('layout_data', 'list'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'SMS Content Management',
            'url' => route('admin.sms.content.list'),
            'icon' => 'fa fa-file'
        ];
        $edit = SmsContent::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                //'title' => 'required|string|max:100',
                'content' => 'required|string'
            ]);
            if ($edit){
                //$edit->title = $request['title'];
                $edit->status = $request['status'];
                $edit->content = $request['content'];
                $edit->save();
                toastr()->success('Data successfully updated!');
                return redirect('Kobe/sms-content/list?page='.$page);
            }
            toastr()->error('Data not updated!');
            return redirect('Kobe/sms-content/list?page='.$page);
        }
        return view('admin.sms-content.edit', compact('layout_data', 'edit'));

    }

    public function view(Request $request, $id){
        $layout_data= [
            'title' => 'SMS Content Management',
            'url' => route('admin.sms.content.list'),
            'icon' => 'fa fa-file'
        ];
        $view = SmsContent::findOrFail(base64_decode($id));
        return view('admin.sms-content.view', compact('view', 'layout_data'));
    }
}
