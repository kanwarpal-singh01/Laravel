<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function list(){
        $layout_data= [
            'title' => 'Testimonial Management',
            'url' => route('admin.testimonial.list'),
            'icon' => 'fa fa-user'
        ];
        $list = Testimonial::paginate(config('custom-paginate.paginate.number'));
        return view('admin.testimonial.list', compact('layout_data', 'list'));
    }
    public function add(Request $request){
        $layout_data= [
            'title' => 'Testimonial Management',
            'url' => route('admin.testimonial.list'),
            'icon' => 'fa fa-user'
        ];
        if ($request->isMethod('post')){
            $this->validate($request, [
               'first_name' => 'required|string|max:100',
               'last_name' => 'required|string|max:100',
               'description' => 'required|string',
               'designation' => 'required|string|max:200',
               'image' => 'required|mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);

            $testimonial = new Testimonial();
            $testimonial->first_name = $request['first_name'];
            $testimonial->last_name = $request['last_name'];
            $testimonial->description = $request['description'];
            $testimonial->designation = $request['designation'];
            $testimonial->status = $request['status'];
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                if ($file) {
                    $destinationPath = 'public/storage/uploads/testimonial/';
                    $extension = $request->file('image')->getClientOriginalExtension();
                    $filename =  time(). '.' . $extension;
                    $file->move($destinationPath, $filename);
                    $testimonial->image = $filename;
                }
            }
            $testimonial->save();
            toastr()->success('Testimonial successfully added!');
            return redirect()->route('admin.testimonial.list');
        }
        return view('admin.testimonial.add', compact('layout_data'));

    }
    public function edit(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'Testimonial Management',
            'url' => route('admin.testimonial.list'),
            'icon' => 'fa fa-user'
        ];
        $edit = Testimonial::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'description' => 'required|string',
                'designation' => 'required|string|max:200',
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'status' => 'required|in:0,1'
            ]);
            if ($edit){
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    if ($file) {
                        $destinationPath = 'public/storage/uploads/testimonial/';
                        $extension = $request->file('image')->getClientOriginalExtension();
                        $filename =  time(). '.' . $extension;
                        $file->move($destinationPath, $filename);
                        $edit->image = $filename;
                    }
                }
                $edit->first_name = $request['first_name'];
                $edit->last_name = $request['last_name'];
                $edit->description = $request['description'];
                $edit->designation = $request['designation'];
                $edit->status = $request['status'];
                $edit->save();
                toastr()->success('Testimonial successfully updated!');
                return redirect('Kobe/testimonial/list?page='.$page);
            }
            toastr()->error('Testimonial not updated!');
            return redirect('Kobe/testimonial/list?page='.$page);
        }
        return view('admin.testimonial.edit', compact('layout_data', 'edit'));

    }
    public function delete(Request $request, $id){
        $delete = Testimonial::findOrFail(base64_decode($id));
        if ($delete){
            $delete->delete();
            toastr()->success('Testimonial successfully deleted!');
            return redirect()->back();
        }
        toastr()->error('Testimonial not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data= [
            'title' => 'Testimonial Management',
            'url' => route('admin.testimonial.list'),
            'icon' => 'fa fa-user'
        ];
        $view = Testimonial::findOrFail(base64_decode($id));
        return view('admin.testimonial.view', compact('view', 'layout_data'));
    }
}
