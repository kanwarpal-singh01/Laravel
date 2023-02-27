<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\HomeSection1;
use Illuminate\Http\Request;

class HomeSection1Controller extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'About Us Management',
            'url' => route('admin.home.section1.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = HomeSection1::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.home-section1.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'About Us Management',
            'url' => route('admin.home.section1.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'mimes:jpeg,png,jpg,svg',
                'status' => 'required|in:0,1'
            ]);
            $add = new HomeSection1();
            $add->status = $request->status;
            $add->title = $request->title;
            $add->content = $request['content'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/home-section1/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image = $filename;
                }
            }
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.home.section1.list');
        }
        return view('admin.home-section1.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'About Us Management',
            'url' => route('admin.home.section1.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = HomeSection1::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/home-section1/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->content = $request['content'];
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/about-us-management/list?page='.$page);
        }
        return view('admin.home-section1.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = HomeSection1::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Data successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Data not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'About Us Management',
            'url' => route('admin.home.section1.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = HomeSection1::findOrFail(base64_decode($id));
        return view('admin.home-section1.view', compact('view', 'layout_data'));
    }
}
