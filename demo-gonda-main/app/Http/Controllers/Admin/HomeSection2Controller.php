<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\HomeSection2;
use Illuminate\Http\Request;

class HomeSection2Controller extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Features Management',
            'url' => route('admin.home.section2.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = HomeSection2::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.home-section2.list', compact('layout_data','list'));
    }

    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Features Management',
            'url' => route('admin.home.section2.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:0,1'
            ]);
            $add = new HomeSection2();
            $add->status = $request->status;
            $add->title = $request->title;
            $add->content = $request['content'];
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.home.section2.list');
        }
        return view('admin.home-section2.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Features Management',
            'url' => route('admin.home.section2.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = HomeSection2::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'icon' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|in:0,1'
            ]);
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->icon = $request->icon;
            $edit->content = $request['content'];
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/features-management/list?page='.$page);
        }
        return view('admin.home-section2.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = HomeSection2::findOrFail(base64_decode($id));
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
            'title' => 'Features Management',
            'url' => route('admin.home.section2.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = HomeSection2::findOrFail(base64_decode($id));
        return view('admin.home-section2.view', compact('view', 'layout_data'));
    }
}
