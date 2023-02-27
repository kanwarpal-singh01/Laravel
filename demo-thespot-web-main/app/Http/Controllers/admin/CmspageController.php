<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Models\Cmspage;
use App\Models\HomeSetting;
use Session;
use Auth;
use DB;
use App\Models\User;
use App\Models\EmailTemplate;
use Helper;
use Mail;
use View;
use Validator;
use Illuminate\Validation\Validator as ValidationValidator;


class CmspageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //show all static page with filter
    public function index(Request $request)
    {
        $records = Cmspage::where(['status' => '1'])->orderBy('id', 'desc')->paginate(config('Settings.AdminPageLimit'));
        return View::make('admin.cmspage.index')->with(compact('records'));
    }

    //show form for create new static page
    public function create()
    {

        return view('admin/cmspage.create', array('title' => 'Add Cms Page'));
    }
    public function edit(Request $request, $id)
    {
        $record = Cmspage::find(base64_decode($id));
        return view('admin/cmspage.edit', array('record' => $record));
    }

    //save static page
    public function store(Request $request)
    {

        $this->validate(
            $request,
            array(
                "title_en" => "required|max:150",
                'content_en' => 'required',
            )
        );

        $input = $request->all();
        $slug = $this->slugify($request->input('title_en'));
        $input['slug'] = $slug;
        $user = Cmspage::create($input);
        Session::flash('message', 'Cmspage Added successfully!');
        return redirect('admin/cmspage');
    }

    //generate unique slug for staic page
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
        $slugdata = Cmspage::where('slug', $text)->first();

        if ($slugdata) {
            $text =     $text . '-' . rand(10, 100);
        }
        return $text;
    }

    //view static page
    public function view($id)
    {

        $matchThese = ['id' => base64_decode($id)];
        $record = Cmspage::where($matchThese)->first();
        return view('admin/cmspage.view', array('record' => $record, 'title' => 'View Cms Page'));
    }

    //show form for edit staic page


    //update staic page
    public function update(Request $request, $id)
    {
        $input = $request->all();
        $rule = [
            'title_en'=>'required',
            'content_en'=>'required',
            /* 'content_ar'=>'nullable',
            'title_en' => 'required_without:title_ar',
            'content_en' => 'required_with:title_en|max:5000' */
        ];

        try {
            $validator = Validator::make($input, $rule);

            $validator = Validator::make($request->all(), [
                'title_en' => 'required',
                'content_en' => 'required|max:5000',
                'title_ar' => 'required_without:title_en',
                'content_ar' => 'required_without:content_en|max:5000'
            ]);
            //pr($request->all());die;
            if ($validator->fails()) {
                $error = Helper::WebValidationSet($validator->errors());
                Session::flash('error', $error);
                return redirect()->back();
            }
            $record  = Cmspage::findOrFail(base64_decode($id));
            $record->Fill($input)->save();
            Session::flash('message', 'Cmspage updated successfully!');
            return redirect()->route('cmspages');
        } catch (\Throwable $th) {
            pr($th->getMessage());die;
            return redirect()->back()->with('error', 'Error:' . $th->getMessage());
        }
    }

    //delete staic page
    public function destroy($id)
    {
        $record = Cmspage::find(base64_decode($id));
        $record->delete();

        return redirect('admin/cmspage')->with('message', 'Cmspage Delete successfully');
    }

    //activate static page
    public function activate($id)
    {

        Cmspage::where('id', base64_decode($id))->update(['status' => 1]);
        return redirect()->back()->with('message', 'Cmspage activate successfully');
    }

    //deactivate staic page
    public function deactivate($id)
    {
        Cmspage::where('id', base64_decode($id))->update(['status' => 0]);
        return redirect()->back()->with('message', 'Cmspage deactivate successfully');
    }


    public function home_setting()
    {
        $record = DB::table('home-setting')->where('id', 1)->first();
        return view('admin/cmspage.home_setting', compact('record'));
    }

    public function home_setting_update(Request $request, $id)
    {
        $record   =   HomeSetting::find(base64_decode($id));
        $input = $request->all();
        $record->fill($input)->save();
        return redirect()->back()->with('message', 'Home setting updated successfully');
    }
}
