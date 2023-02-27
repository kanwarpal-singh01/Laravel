<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\HomeSection3;
use Illuminate\Http\Request;

class HomeSection3Controller extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Video Management',
            'url' => route('admin.home.section3.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = HomeSection3::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.home-section3.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Video Management',
            'url' => route('admin.home.section3.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'video' => 'required|mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts',
                'status' => 'required|in:0,1'
            ]);
            $add = new HomeSection3();
            $add->status = $request->status;
            $add->title = $request->title;
            if ($request->hasFile('video')) {
                $file = $request->file('video');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/home-section3/';
                    $extension = $request->file('video')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->video = $filename;
                }
            }
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.home.section3.list');
        }
        return view('admin.home-section3.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Video Management',
            'url' => route('admin.home.section3.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = HomeSection3::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'video' => 'mimes:mpeg,ogg,mp4,webm,3gp,mov,flv,avi,wmv,ts',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('video')) {
                $file = $request->file('video');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/home-section3/';
                    $extension = $request->file('video')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->video = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/video-management/list?page='.$page);
        }
        return view('admin.home-section3.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = HomeSection3::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Data successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Data not deleted!');
        return redirect()->back();
    }

}
