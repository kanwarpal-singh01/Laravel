<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Footer;
use Illuminate\Http\Request;

class FooterController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Footer Management',
            'url' => route('admin.footer.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = Footer::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.footer.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Footer Management',
            'url' => route('admin.footer.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'google_pay_url' => 'required|string|max:255',
                'app_store_url' => 'required|string|max:255',
                'content' => 'required|string|max:255',
                'image' => 'required|mimes:jpeg,png,jpg,svg',
                'status' => 'required|in:0,1'
            ]);
            $add = new Footer();
            $add->status = $request->status;
            $add->title = $request->title;
            $add->app_store_url = $request->app_store_url;
            $add->google_pay_url = $request->google_pay_url;
            $add->content = $request['content'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/footer/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image = $filename;
                }
            }
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.footer.list');
        }
        return view('admin.footer.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Footer Management',
            'url' => route('admin.footer.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = Footer::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'google_pay_url' => 'required|string|max:255',
                'app_store_url' => 'required|string|max:255',
                'content' => 'required|string|max:255',
                'description' => 'required|string',
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/footer/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->app_store_url = $request->app_store_url;
            $edit->google_pay_url = $request->google_pay_url;
            $edit->content = $request['content'];
            $edit->description = $request['description'];
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/footer-management/list?page='.$page);
        }
        return view('admin.footer.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = Footer::findOrFail(base64_decode($id));
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
            'title' => 'Footer Management',
            'url' => route('admin.footer.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = Footer::findOrFail(base64_decode($id));
        return view('admin.footer.view', compact('view', 'layout_data'));
    }

}
