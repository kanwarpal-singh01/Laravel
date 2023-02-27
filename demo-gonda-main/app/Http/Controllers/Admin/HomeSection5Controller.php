<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\HomeSection5;
use Illuminate\Http\Request;

class HomeSection5Controller extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Screenshot Management',
            'url' => route('admin.home.section5.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = HomeSection5::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.home-section5.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Screenshot Management',
            'url' => route('admin.home.section5.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'required|array',
                'image.*' => 'mimes:jpeg,png,jpg,svg',
                'status' => 'required|in:0,1'
            ]);

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    foreach ($file as $image){
                        $add = new HomeSection5();
                        $destinationPath = 'public/storage/uploads/home-section5/';
                        $extension = $image->getClientOriginalExtension();
                        $filename =  time(). '.' . $extension;
                        $image->move($destinationPath, $filename);
                        $add->image = $filename;
                        $add->status = $request->status;
                        $add->title = $request->title;
                        $add->save();
                    }

                    }
            }

            toastr()->success('Data successfully added!');
            return redirect()->route('admin.home.section5.list');
        }
        return view('admin.home-section5.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Screenshot Management',
            'url' => route('admin.home.section5.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = HomeSection5::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/home-section5/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/screenshot-management/list?page='.$page);
        }
        return view('admin.home-section5.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = HomeSection5::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Data successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Data not deleted!');
        return redirect()->back();
    }

}
