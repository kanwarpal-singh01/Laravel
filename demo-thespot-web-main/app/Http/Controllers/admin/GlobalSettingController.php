<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Sptconfigs;
use Illuminate\Foundation\Validation\ValidatesRequests;

class GlobalSettingController extends Controller
{
    
	public function __construct() {
        $this->middleware('auth');
    }
    public function index(){
    	$config = Sptconfigs::all();
        dd($config);die();
    	 return view('admin.globalsetting',array('config'=>$config));
    }

    //show global setting edit form
    public function config(){
    	$config = Sptconfigs::where(['status'=>'1'])->get();        
        return view('admin.globalsetting',array('config'=>$config ));
    	
    }


    //update global setting
    public function updateglobalsetting(Request $request){
        $id = $request->id;
        $value = $request->value;
        $msg=false;
        $data  = Sptconfigs::findOrFail($id);
        if(!empty($data)){
            $data->value =  $request->value;
            if($data->save()){
                return \Response::json(true);
            }else{
                return \Response::json($msg);
            }
        }else{
            return \Response::json($msg);
        }
    }
    public function create(Request $request)
    {
        if($request->isMethod("post"))
        {
            $this->validate($request,array(
                'title'=>"required",
                'value'=>"required",
                'slug'=>"required",
                'type'=>"required",
                'categorytype'=>"required",
            ));
            $input = $request->all();
            if(!empty($input))
            {
                $data= Sptconfigs::create($input);
                if(!empty($data))
                {
                    return redirect("admin/config");
                }
            }
        }
        return view("admin.create_global_key");

    }
}
