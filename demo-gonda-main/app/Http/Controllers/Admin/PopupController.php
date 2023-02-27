<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PopupManagement;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'PopUp Management',
            'url' => route('admin.popup.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = PopupManagement::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/popup/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->description = $request['description'];
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/pop-up-management/list?page='.$page);
        }
        return view('admin.popup-management.edit', compact('layout_data', 'edit'));
    }

    public function list(){
        $layout_data = [
            'title' => 'PopUp Management',
            'url' => route('admin.popup.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = PopupManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.popup-management.list', compact('layout_data','list'));
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'PopUp Management',
            'url' => route('admin.popup.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = PopupManagement::findOrFail(base64_decode($id));
        return view('admin.popup-management.view', compact('view', 'layout_data'));
    }
}
