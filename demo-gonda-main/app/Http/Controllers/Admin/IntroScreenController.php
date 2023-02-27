<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\IntroScreen;
use Illuminate\Http\Request;

class IntroScreenController extends Controller
{
    public function list(){
        $layout_data= [
            'title' => 'App Intro Screen',
            'url' => route('admin.intro.screen.list'),
            'icon' => 'fa fa-file-image-o'
        ];
        $list = IntroScreen::paginate(config('custom-paginate.paginate.number'));
        return view('admin.intro-screen.list', compact('layout_data', 'list'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data= [
            'title' => 'App Intro Screen',
            'url' => route('admin.intro.screen.list'),
            'icon' => 'fa fa-file-image-o'
        ];
        $edit = IntroScreen::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
                'image' => 'mimes:jpg,jpeg,png,svg|max:50000',
                'title' => 'required|string|max:100',
                'content' => 'required|string'
            ]);
            if ($edit){
                if ($request->hasFile('image')) {
                    $file = $request->file('image');
                    if ($file) {
                        $destinationPath = 'public/storage/uploads/intro-screen/';
                        $extension = $request->file('image')->getClientOriginalExtension();
                        $filename =  time(). '.' . $extension;
                        $file->move($destinationPath, $filename);
                        $edit->image = $filename;
                    }
                }
                $edit->title = $request['title'];
                $edit->content = $request['content'];
                $edit->save();
                toastr()->success('Data successfully updated!');
                return redirect('Kobe/intro-screen/list?page='.$page);
            }
            toastr()->error('Data not updated!');
            return redirect('Kobe/intro-screen/list?page='.$page);
        }
        return view('admin.intro-screen.edit', compact('layout_data', 'edit'));

    }
    public function view(Request $request, $id){
        $layout_data= [
            'title' => 'App Intro Screen',
            'url' => route('admin.intro.screen.list'),
            'icon' => 'fa fa-file-image-o'
        ];
        $view = IntroScreen::findOrFail(base64_decode($id));
        return view('admin.intro-screen.view', compact('view', 'layout_data'));
    }
}
