<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\MarketingAdManagement;
use Illuminate\Http\Request;

class MarketingAdManagementController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => "Marketing Ad Management",
            'url' => route('admin.marketing.ad.management.list'),
            'icon' => "fas fa-ad",
        ];
        $list = MarketingAdManagement::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.marketing-ad-management.list', compact('layout_data', 'list'));
    }

    public function add(Request $request){
        $layout_data = [
            'title' => "Marketing Ad Management",
            'url' => route('admin.marketing.ad.management.list'),
            'icon' => "fas fa-ad",
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'url' => 'required|url|max:100',
                'description' => 'required|string',
                'status' => 'required|in:0,1',
                'image' => 'required|mimes:jpg,jpeg,png|max:50000',
            ]);
            $add = new MarketingAdManagement();
            $add->title = $request['title'];
            $add->url = $request['url'];
            $add->description = $request['description'];
            $add->status = $request['status'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/marketing-ad/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $add->image = $filename;
                }
            }
            $add->save();
            toastr()->success('Marketing Ad successfully added!');
            return redirect()->route('admin.marketing.ad.management.list');
        }
        return view('admin.marketing-ad-management.add', compact('layout_data'));
    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => "Marketing Ad Management",
            'url' => route('admin.marketing.ad.management.list'),
            'icon' => "fas fa-ad",
        ];
        $edit = MarketingAdManagement::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|regex:/^[a-zA-Z\s]+$/|max:50',
                'url' => 'required|url|max:100',
                'description' => 'required|string',
                'status' => 'required|in:0,1',
                'image' => 'mimes:jpg,jpeg,png|max:50000',
            ]);
            $edit->title = $request['title'];
            $edit->url = $request['url'];
            $edit->description = $request['description'];
            $edit->status = $request['status'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/marketing-ad/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $edit->image = $filename;
                }
            }
            $edit->save();
            toastr()->success('Marketing Ad successfully updated!');
            return redirect('Kobe/marketing-ad-management/list?page='.$page);
        }

        return view('admin.marketing-ad-management.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = MarketingAdManagement::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Marketing Ad successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Marketing Ad not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => "Marketing Ad Management",
            'url' => route('admin.marketing.ad.management.list'),
            'icon' => "fas fa-ad",
        ];
        $view = MarketingAdManagement::findOrFail(base64_decode($id));
        return view('admin.marketing-ad-management.view', compact('view', 'layout_data'));
    }
}
