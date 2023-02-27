<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\NotaryDetail;
use Illuminate\Http\Request;

class NotaryDetailsController extends Controller
{
    public function list(){
        $layout_data= [
            'title' => 'Notary Details',
            'url' => route('admin.notary.details.list'),
            'icon' => 'fa fa-file'
        ];
        $list = NotaryDetail::paginate(config('custom-paginate.paginate.number'));
        return view('admin.notary-details.list', compact('layout_data', 'list'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'Notary Details',
            'url' => route('admin.notary.details.list'),
            'icon' => 'fa fa-file'
        ];
        $edit = NotaryDetail::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'amount' => 'required|numeric',
                'content' => 'required|string',
                'content_android' => 'required|string',
                'status' => 'required|in:0,1'

            ]);
            if ($edit){
                $edit->amount = $request['amount'];
                $edit->status = $request['status'];
                $edit->content = $request['content'];
                $edit->content_android = $request['content_android'];
                $edit->save();
                toastr()->success('Data successfully updated!');
                return redirect('Kobe/notary-details/list?page='.$page);
            }
            toastr()->error('Data not updated!');
            return redirect('Kobe/notary-details/list?page='.$page);
        }
        return view('admin.notary-details.edit', compact('layout_data', 'edit'));

    }
    public function view(Request $request, $id){
        $layout_data= [
            'title' => 'Notary Details',
            'url' => route('admin.notary.details.list'),
            'icon' => 'fa fa-file'
        ];
        $view = NotaryDetail::findOrFail(base64_decode($id));
        return view('admin.notary-details.view', compact('view', 'layout_data'));
    }
}
