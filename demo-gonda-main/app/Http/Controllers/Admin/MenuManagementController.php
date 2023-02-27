<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\MenuManagement;
use Illuminate\Http\Request;

class MenuManagementController extends Controller
{
    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Menu Management',
            'url' => route('admin.menu.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $edit = MenuManagement::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'serial_number' => 'required|numeric|min:1',
                'title' => 'required|string|max:255',
                'status' => 'required|in:0,1'
            ]);
            $edit->status = $request->status;
            $edit->title = $request->title;
            $edit->serial_number = $request->serial_number;
            $edit->save();
            toastr()->success('Data successfully updated!');
            return redirect('Kobe/menu-management/list?page='.$page);
        }
        return view('admin.menu-management.edit', compact('layout_data', 'edit'));
    }

    public function list(){
        $layout_data = [
            'title' => 'Menu Management',
            'url' => route('admin.menu.list'),
            'icon' => 'fa fa-picture-o'
        ];
        $list = MenuManagement::orderBy('id')->paginate(config('custom-paginate.paginate.number'));;
        return view('admin.menu-management.list', compact('layout_data','list'));
    }
}
