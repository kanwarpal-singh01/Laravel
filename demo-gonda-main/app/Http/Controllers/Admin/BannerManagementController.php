<?php

namespace App\Http\Controllers\Admin;

use App\Model\BannerManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BannerManagementController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Banner Management',
            'url' => route('admin.banner.management.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = BannerManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.banner-management.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Banner Management',
            'url' => route('admin.banner.management.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'image' => 'required|mimes:jpeg,png,jpg,svg',
                'status' => 'required|in:0,1'
            ]);
            $banner = new BannerManagement();
            $banner->status = $request->status;
            $banner->title = $request->title;
            $banner->content = $request['content'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/banner/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $banner->image = $filename;
                }
            }
            $banner->save();
            toastr()->success('Banner successfully added!');
            return redirect()->route('admin.banner.management.list');
        }
        return view('admin.banner-management.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Banner Management',
            'url' => route('admin.banner.management.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = BannerManagement::findOrFail(base64_decode($id));
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
                    $destinationPath = 'public/storage/uploads/banner/';
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
            toastr()->success('Banner successfully updated!');
            return redirect('Kobe/banner-management/list?page='.$page);
        }
        return view('admin.banner-management.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = BannerManagement::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Banner successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Banner not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'Banner Management',
            'url' => route('admin.banner.management.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = BannerManagement::findOrFail(base64_decode($id));
        return view('admin.banner-management.view', compact('view', 'layout_data'));
    }
}
