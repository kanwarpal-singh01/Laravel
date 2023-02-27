<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\KnowledgeCenter;
use Illuminate\Http\Request;

class KnowledgeCenterController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Knowledge Center',
            'url' => route('admin.knowledge.center.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = KnowledgeCenter::paginate(config('custom-paginate.paginate.number'));;
        return view('admin.knowledge-center.list', compact('layout_data','list'));
    }
    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Knowledge Center',
            'url' => route('admin.knowledge.center.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $category = Category::where('status', 1)->pluck('name', 'id');
        if ($request->isMethod('post')){//dd($request->all());
            $request->validate([
                'title' => 'required|string|max:255',
                'document' => 'mimes:pdf',
                'category_id' => 'required',
                'description' => 'sometimes|nullable|string',
                'status' => 'required|in:0,1'
            ]);
            $add = new KnowledgeCenter();
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/knowledge-center/';
                    $extension = $request->file('document')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->document = $filename;
                }
            }
            $add->status = $request['status'];
            $add->title = $request['title'];
            $add->description = $request['description'];
            $add->category_id = implode(',', $request['category_id']);
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.knowledge.center.list');
        }
        return view('admin.knowledge-center.add', compact('layout_data','category'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Knowledge Center',
            'url' => route('admin.knowledge.center.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $category = Category::where('status', 1)->get();
        $edit = KnowledgeCenter::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:255',
                'document' => 'mimes:pdf',
                'description' => 'sometimes|nullable|string',
                'status' => 'required|in:0,1'
            ]);
            if ($request->hasFile('document')) {
                $file = $request->file('document');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/knowledge-center/';
                    $extension = $request->file('document')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->document = $filename;
                }
            }
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->description = $request->description;
            if (!empty($request['category_id'])){
                $edit->category_id = implode(',', $request['category_id']);
            }
            else{
                $edit->category_id = null;
            }
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/knowledge-center/list?page='.$page);
        }
        return view('admin.knowledge-center.edit', compact('layout_data', 'edit', 'category'));
    }
    public function delete(Request $request, $id){
        $delete = KnowledgeCenter::findOrFail(base64_decode($id));
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
            'title' => 'Knowledge Center',
            'url' => route('admin.knowledge.center.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = KnowledgeCenter::findOrFail(base64_decode($id));
        return view('admin.knowledge-center.view', compact('view', 'layout_data'));
    }

    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            //get filename with extension
            $filenamewithextension = $request->file('upload')->getClientOriginalName();

            //get filename without extension
            $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

            //get file extension
            $extension = $request->file('upload')->getClientOriginalExtension();

            //filename to store
            $filenametostore = $filename.'_'.time().'.'.$extension;

            //Upload File
            $destinationPath = 'public/storage/uploads/';

            $request->file('upload')->move($destinationPath, $filenametostore);
            //$request->file('upload')->storeAs('public/uploads', $filenametostore);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('public/storage/uploads/'.$filenametostore);
            $msg = 'Image successfully uploaded';
            $re = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            // Render HTML output
            @header('Content-type: text/html; charset=utf-8');
            echo $re;
        }
    }


}
