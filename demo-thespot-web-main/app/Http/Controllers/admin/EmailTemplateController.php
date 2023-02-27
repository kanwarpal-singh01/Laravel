<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use View;
use Session;
use Validator;
use Helper;

class EmailTemplateController extends Controller
{
	//show email templates with filter
    public function index(Request $request){
    	$query = $request->input('q');
        if ($query)
        {
           $records = EmailTemplate::orderBy('id', 'ASC')->where('status','=',1)->where('title', 'LIKE', "%$query%")->orwhere('subject', 'LIKE', "%$query%")->orwhere('content', 'LIKE', "%$query%")->orWhere('slug','LIKE',"%$query%")->paginate(config('Settings.AdminPageLimit'));
            return view('admin/emailtemplate.index',  array('records' => $records,'query' => $query,'title' => 'Cms Page'));  
        
        } else {
          
           $records = EmailTemplate::orderBy('id', 'ASC')->where('status',1)->paginate(config('Settings.AdminPageLimit'));
           
           return view('admin/emailtemplate.index',  array('records' => $records,'title' => 'Cms Page'));  
        
        }
    }

    //show form for create email template
    public function create(){
    	return view('admin/emailtemplate.create');
    }

    //save email template
    public function store(Request $request){
    	$this->validate($request,[
    		'title' => 'required|max:255',
    		'subject' => 'required|max:255',
    		'content' => 'required',
    	]);
    	$input = $request->all();
    	$slug = $this->slugify($request->input('title'));
    	$input['slug'] = $slug;
    	$emailtemplate = EmailTemplate::create($input);
    	Session::flash('message', 'Email Template Added successfully!');
		return redirect('admin/emailtemplate');
    }

    //generate unique slug for email template
    static public function slugify($text)
	{
		  // replace non letter or digits by -
		  $text = preg_replace('~[^\pL\d]+~u', '-', $text);

		  // transliterate
		  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

		  // remove unwanted characters
		  $text = preg_replace('~[^-\w]+~', '', $text);

		  // trim
		  $text = trim($text, '-');

		  // remove duplicate -
		  $text = preg_replace('~-+~', '-', $text);

		  // lowercase
		  $text = strtolower($text);

		  if (empty($text)) {
			return 'n-a';
		  }

		 
		// check slug exist in database
		$slugdata = Emailtemplate::where('slug',$text)->first();
	    if($slugdata) {
			$text = 	$text.'-'.rand(10,100);
		}  
		return $text;
	}

	//view email template
	public function view($id){
		$where = ['id' => base64_decode($id)];
		$data = EmailTemplate::where($where)->first();
		return view('admin/emailtemplate.view', array('record' => $data, 'title' => 'View Email Template'));
	}

	//show form for edit email template
	public function edit($id){
		$where = ['id' => base64_decode($id)];
		$data = EmailTemplate::where($where)->first();
		return view('admin/emailtemplate.edit',array('record' => $data, 'title' => 'Edit Email Template'));
	}

	//update email template
	public function update(Request $request, $id){
		$validator = Validator::make($request->all(), [
			'title' => 'required|max:255',
			'subject' => 'required|max:255',
			'content' => 'required',
		]);
		if ($validator->fails()) {
			$error = Helper::WebValidationSet($validator->errors());
			if (!empty($error)) {
				Session::flash('error', $error);
				return back();
			}
		}
		$input = $request->all();
		$record = EmailTemplate::find(base64_decode($id));
		if($record){
			$record->fill($input)->save();
			Session::flash('message','Email Template updated successfully!');	
		}else{
			Session::flash('error','Internal server error. Please try again!');
		}
		
		return redirect('admin/emailtemplate');
	}

	//delete email template
	public function delete($id){
		$where = ['id'=> base64_decode($id)];
		$record = EmailTemplate::where($where)->delete();
		if($record){
			Session::flash('message','Email Template deleted successfully!');
		}else{
			Session::flash('error','Internal server error. Please try again!');
		}
		return redirect('admin/emailtemplate');
	}
    
}
