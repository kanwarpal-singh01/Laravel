<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Category Management',
            'url' => route('admin.category.list'),
            'icon' => 'fa fa-outdent'
        ];
        $list= Category::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));
        return view('admin.category-management.list', compact('layout_data','list'));
    }

    public function add(Request $request){
        $layout_data = [
            'title' => 'Category Management',
            'url' => route('admin.category.list'),
            'icon' => 'fa fa-outdent'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'name' =>'required|unique:categories',
                'content' => 'required',
                'status' => 'required|in:0,1'
            ]);
            $category = new Category();
            $category->name = $request['name'];
            $category->content = $request['content'];
//            if ($request->hasFile('image')) {
//                $file = $request->file('image');
//                if ($file) {
//                    $destinationPath = 'public/storage/uploads/category/';
//                    $extension = $request->file('image')->getClientOriginalExtension();
//                    $filename =  time(). '.' . $extension;
//                    $file->move($destinationPath, $filename);
//                    $category->image = $filename;
//                }
//            }
            $category->status = $request['status'];
            $category->save();
            toastr()->success('Category successfully added!');
            return redirect()->route('admin.category.list');
        }
        return view('admin.category-management.add', compact('layout_data'));

    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Category Management',
            'url' => route('admin.category.list'),
            'icon' => 'fa fa-outdent'
        ];
        $edit = Category::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'name' =>'required|unique:categories,name,'.base64_decode($id),
                'content' => 'required',
                'status' => 'required|in:0,1'
            ]);
            $edit->name = $request['name'];
            $edit->content = $request['content'];
//            if ($request->hasFile('image')) {
//                $file = $request->file('image');
//                if ($file) {
//                    $destinationPath = 'public/storage/uploads/category/';
//                    $extension = $request->file('image')->getClientOriginalExtension();
//                    $filename =  time(). '.' . $extension;
//                    $file->move($destinationPath, $filename);
//                    $edit->image = $filename;
//                }
//            }
            $edit->status = $request['status'];
            $edit->save();
            toastr()->success('Category successfully updated!');
            return redirect('Kobe/category-management/list?page='.$page);
        }
        return view('admin.category-management.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = Category::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Category successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Category not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'Category Management',
            'url' => route('admin.category.list'),
            'icon' => 'fa fa-outdent'
        ];
        $view = Category::findOrFail(base64_decode($id));
        return view('admin.category-management.view', compact('view', 'layout_data'));
    }
}
