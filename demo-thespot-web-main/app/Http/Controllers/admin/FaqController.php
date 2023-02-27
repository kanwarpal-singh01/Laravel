<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Faq;
use Session;
use Auth;
use DB;
use App\Models\User;
use App\Models\EmailTemplate;
use Helper;
use Mail;
use Validator;

class FaqController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //show all static page with filter
    public function index(Request $request)
    {
        $query = $request->input('q');
        if ($query) {
            $records = Faq::orderBy('id', 'desc')->paginate(config('Settings.AdminPageLimit'));
            return view('admin/faq.index', array('records' => $records, 'query' => $query));
        } else {
            $records = Faq::orderBy('id', 'desc')->paginate(config('Settings.AdminPageLimit'));
            return view('admin/faq.index',  array('records' => $records, 'title' => 'Faq'));
        }
    }

    //show form for create new static page
    public function create()
    {
        return view('admin/faq.create', array('title' => 'Add Faq'));
    }

    //save static page
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'question_en' => 'required|max:300',
            "answer_en" => "required|max:5000",
            'question_ar' => 'nullable|max:300',
            "answer_ar" => "nullable|max:5000",

        ]);
        //pr($request->all());die;
        if ($validator->fails()) {
            $error = Helper::WebValidationSet($validator->errors());
            Session::flash('error', $error);
            return redirect()->back();
        }
        $input = $request->all();
        $user = Faq::create($input);
        Session::flash('message', 'Faq Added successfully!');
        return redirect('admin/faq');
    }



    //view static page
    public function view($id)
    {

        $matchThese = ['id' => base64_decode($id)];
        $record = Faq::where($matchThese)->first();
        return view('admin/faq.view', array('record' => $record, 'title' => 'View Faq'));
    }

    //show form for edit staic page 
    public function edit($id)
    {
        $record = Faq::find(base64_decode($id));
        return view('admin/faq.edit', array('record' => $record, 'title' => 'Edit Faq'));
    }

    //update staic page
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'question_en' => 'required|max:300',
            "answer_en" => "required|max:5000",
            'question_ar' => 'nullable|max:300',
            "answer_ar" => "nullable|max:5000",

        ]);
        //pr($request->all());die;
        if ($validator->fails()) {
            $error = Helper::WebValidationSet($validator->errors());
            Session::flash('error', $error);
            return redirect()->back();
        }

        $record   =   Faq::find(base64_decode($id));
        $input = $request->all();
        $record->fill($input)->save();

        Session::flash('message', 'Faq updated successfully!');
        return redirect('admin/faq');
    }

    //delete staic page
    public function destroy($id)
    {
        $record = Faq::find(base64_decode($id));
        $record->delete();

        return redirect('admin/faq')->with('message', 'Faq Deleted successfully');
    }
}
