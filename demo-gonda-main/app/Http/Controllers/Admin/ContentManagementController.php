<?php

namespace App\Http\Controllers\Admin;

use App\Model\ContentManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    //this is for ios
    public function list(){
        $layout_data= [
            'title' => 'Legal Management For IOS',
            'url' => route('admin.content.management.list'),
            'icon' => 'fa fa-file'
        ];
        $list = ContentManagement::where('type', 1)->paginate(config('custom-paginate.paginate.number'));
        return view('admin.content-management.list', compact('layout_data', 'list'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'Legal Management For Ios',
            'url' => route('admin.content.management.list'),
            'icon' => 'fa fa-file'
        ];
        $edit = ContentManagement::findOrFail(base64_decode($id));
        if(!empty($edit)){
            if ($request->isMethod('post')){
                $request->validate([
                    'title' => 'required|string|max:100',
                    'content' => 'required|string'
                ]);
                if ($edit){
                    $edit->title = $request['title'];
                    $edit->status = $request['status'];
                    $edit->content = $request['content'];
                    $edit->save();
                    toastr()->success('Content successfully updated!');
                    return redirect('Kobe/content-management/list?page='.$page);
                }
                toastr()->error('Content not updated!');
                return redirect('Kobe/content-management/list?page='.$page);
            }
        }
        else{
            toastr()->error('Something went wrong.Please try again!');
            return redirect()->back();
        }
        
        return view('admin.content-management.edit', compact('layout_data', 'edit'));

    }
    public function view(Request $request, $id){
        $layout_data= [
            'title' => 'Legal Management For IOS',
            'url' => route('admin.content.management.list'),
            'icon' => 'fa fa-file'
        ];
        $view = ContentManagement::findOrFail(base64_decode($id));
        return view('admin.content-management.view', compact('view', 'layout_data'));
    }
    //this is for ios

    // this is for android

    public function listForAndroid(Request $request){

        $layout_data= [
            'title' => 'Legal Management For Android',
            'url' => route('admin.content.management.list.android'),
            'icon' => 'fa fa-file'
        ];
        $list = ContentManagement::where('type', 2)->paginate(config('custom-paginate.paginate.number'));
        return view('admin.content-management-for-android.list', compact('layout_data', 'list'));
    }

    public function editForAndroid(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'Legal Management For Android',
            'url' => route('admin.content.management.list.android'),
            'icon' => 'fa fa-file'
        ];
        $edit = ContentManagement::findOrFail(base64_decode($id));
        if(!empty($edit)){
            if ($request->isMethod('post')){
                $request->validate([
                    'title' => 'required|string|max:100',
                    'content' => 'required|string'
                ]);
                if ($edit){
                    $edit->title = $request['title'];
                    $edit->status = $request['status'];
                    $edit->content = $request['content'];
                    $edit->save();
                    toastr()->success('Content successfully updated!');
                    return redirect('Kobe/content-management-for-android/list?page='.$page);
                }
                toastr()->error('Content not updated!');
                return redirect('Kobe/content-management-for-android/list?page='.$page);
            }
        }
        else{
            toastr()->error('Something went wrong.Please try again!');
            return redirect()->back();
        }
        
        return view('admin.content-management-for-android.edit', compact('layout_data', 'edit'));

    }

    public function viewForAndroid(Request $request, $id){
        $layout_data= [
            'title' => 'Legal Management For Android',
            'url' => route('admin.content.management.list.android'),
            'icon' => 'fa fa-file'
        ];
        $view = ContentManagement::findOrFail(base64_decode($id));
        return view('admin.content-management-for-android.view', compact('view', 'layout_data'));
    }
}   
