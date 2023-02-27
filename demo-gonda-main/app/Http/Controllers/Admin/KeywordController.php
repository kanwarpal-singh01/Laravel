<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;

use App\Model\Keyword;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class KeywordController extends Controller
{
    public function list(Request $request){
        $layout_data = [
            'title' => 'Keyword Management',
            'url' => route('admin.keyword.list'),
            'icon' => 'fa fa-outdent'
        ];
        $list= Keyword::where('is_deleted', 0)->orderBy('position_order')->paginate(config('custom-paginate.paginate.number'));

        $position = $request['position'];
        $i=1;
        // Update Orting Data
        if ($position){
            foreach($position as $k=>$v){
                $sql = Keyword::where('id', $v)->update(['position_order' => $i]);
                $i++;
            }
        }
        return view('admin.keywords.list', compact('layout_data','list'));
    }

    public function add(Request $request){
        $layout_data = [
            'title' => 'Keyword Management',
            'url' => route('admin.keyword.list'),
            'icon' => 'fa fa-outdent'
        ];
        if ($request->isMethod('post')){
            $request->validate([
                'keyword' =>'required|regex:/^\S*$/u|unique:keywords',
                'question' => 'required',
                'status' => 'required|in:0,1'
            ],[
                'keyword.regex' => 'Space are not allowed.'
            ]);
            $keyword = new Keyword();
            $keyword->keyword = $request['keyword'];
            $keyword->question = $request['question'];
            $keyword->status = $request['status'];
            $keyword->save();
            toastr()->success('Data successfully added!');
            return redirect()->route('admin.keyword.list');
        }
        return view('admin.keywords.add', compact('layout_data'));

    }

    public function edit(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'Keyword Management',
            'url' => route('admin.keyword.list'),
            'icon' => 'fa fa-outdent'
        ];
        $edit = Keyword::findOrFail(base64_decode($id));
        if($request->isMethod('post')) {
            $request->validate([
                'keyword' =>'required|regex:/^\S*$/u|unique:keywords,keyword,'.base64_decode($id),
                'question' => 'required',
                'status' => 'required|in:0,1'
            ],[
                'keyword.regex' => 'Space are not allowed.'
            ]);
            $edit->keyword = $request['keyword'];
            $edit->question = $request['question'];
            $edit->status = $request['status'];
            $edit->save();
            toastr()->success('Data updated!');
            return redirect('Kobe/keyword/list?page='.$page);
        }
        return view('admin.keywords.edit', compact('layout_data', 'edit'));
    }

    public function delete(Request $request, $id){
        $delete = Keyword::findOrFail(base64_decode($id));

        if ($delete){
            if ($delete->is_deleted == 0){
                $delete->is_deleted = 1;
                $delete->save();
                toastr()->success('Data successfully deleted!');
                return redirect()->back();
            }
        }
        toastr()->error('Data not deleted!');
        return redirect()->back();
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => 'Keyword Management',
            'url' => route('admin.keyword.list'),
            'icon' => 'fa fa-outdent'
        ];
        $view = Keyword::findOrFail(base64_decode($id));
        return view('admin.keywords.view', compact('view', 'layout_data'));
    }
}
