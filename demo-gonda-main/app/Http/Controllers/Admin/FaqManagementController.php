<?php

namespace App\Http\Controllers\Admin;

use App\Model\FaqManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqManagementController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'FAQ Management',
            'url' => route('admin.faq.management.list'),
            'icon' => 'fa fa-question-circle'
        ];
        $list = FaqManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));
        return view('admin.faq-management.list', compact('layout_data', 'list'));
    }

    public function add(Request $request){
        $layout_data = [
            'title' => 'FAQ Management',
            'url' => route('admin.faq.management.list'),
            'icon' => 'fa fa-question-circle'
        ];
        if ($request->isMethod('post')){
            $request->validate([
               'question' => 'required|string',
               'answer' => 'required|string',
               'status' => 'required|in:0,1'
            ]);
           $faq = new FaqManagement();
           $faq->question = $request['question'];
           $faq->answer = $request['answer'];
           $faq->status = $request['status'];
           $faq->save();
            toastr()->success('FAQ successfully added!');
            return redirect()->route('admin.faq.management.list');
        }

        return view('admin.faq-management.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'FAQ Management',
            'url' => route('admin.faq.management.list'),
            'icon' => 'fa fa-question-circle'
        ];
        $edit = FaqManagement::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'question' => 'required|string',
                'answer' => 'required|string',
                'status' => 'required|in:0,1'
            ]);
            $edit->question = $request['question'];
            $edit->answer = $request['answer'];
            $edit->status = $request['status'];
            $edit->save();
            toastr()->success('FAQ successfully updated!');
            return redirect('Kobe/faq-management/list?page='.$page);
        }
        return view('admin.faq-management.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = FaqManagement::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('FAQ successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('FAQ not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'FAQ Management',
            'url' => route('admin.faq.management.list'),
            'icon' => 'fa fa-question-circle'
        ];
        $view = FaqManagement::findOrFail(base64_decode($id));
        return view('admin.faq-management.view', compact('layout_data', 'view'));
    }
}
