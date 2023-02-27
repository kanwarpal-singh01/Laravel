<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\MarketPlace;
use Illuminate\Http\Request;

class MarketPlaceController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'Market Place',
            'url' => route('admin.market.place.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = MarketPlace::paginate(config('custom-paginate.paginate.number'));;
        return view('admin.market-place.list', compact('layout_data','list'));
    }
    public function add(Request $request){//dd($request->all());
        $layout_data = [
            'title' => 'Market Place',
            'url' => route('admin.market.place.list'),
            'icon' => 'fa fa-picture-o'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'sometimes|nullable|string',
                'status' => 'required|in:0,1'
            ]);
            $add = new MarketPlace();
            $add->status = $request['status'];
            $add->title = $request['title'];
            $add->description = $request['description'];
            $add->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.market.place.list');
        }
        return view('admin.market-place.add', compact('layout_data'));
    }
    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Market Place',
            'url' => route('admin.market.place.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = MarketPlace::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'title' => 'required|string|max:100',
                'description' => 'sometimes|nullable|string',
                'status' => 'required|in:0,1'
            ]);
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->description = $request->description;
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/market-place/list?page='.$page);
        }
        return view('admin.market-place.edit', compact('layout_data', 'edit'));
    }
    public function delete(Request $request, $id){
        $delete = MarketPlace::findOrFail(base64_decode($id));
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
            'title' => 'Market Place',
            'url' => route('admin.market.place.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $view = MarketPlace::findOrFail(base64_decode($id));
        return view('admin.market-place.view', compact('view', 'layout_data'));
    }
}
