<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use DB;
use App\User;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return 'Development Mode ....';
       $home_setting=DB::table('home-setting')->find(1);

       $recruiters=DB::table('users')->where('role','recruiter')->limit(8)->get();

       $faqs=DB::table('faq')->take(3)->get();
     
       return view('home',compact('home_setting','faqs','recruiters'));
    }

    public function form(Request $request) {
      $file = $request->file('user_document');
      $ext = $file->getClientOriginalExtension();
      $uploadFile=$file->getClientOriginalName();
        $destinationPath = 'uploads/';
        $file->move($destinationPath,$file->getClientOriginalName());
                if($ext=='docx' || $ext == "doc" || $ext == "rtf"){
                    $result=$this->convertToText($destinationPath.$uploadFile);
                }
      return view('form', array('data'=>$result));
   }
public function documentView(Request $request)
    {
        file_put_contents("uploads/name.doc",$request['comment']);
        return redirect('download');
    }

    public function convertToText($file)
    {
        if (isset($file) && !file_exists($file)) {
            return 'File Does Not exists';
        }
        $fileInformation = pathinfo($file);
        $extension = $fileInformation['extension'];
        if ($extension == 'doc' || $extension == 'docx') {
            if ($extension == 'doc') {
                return $this->extract_doc($file);
            } elseif ($extension == 'docx') {
                return $this->extract_docx($file);
            }
        } else {
            return 'Invalid File Type, please use doc or docx word document file.';
        }

    }

    private function extract_doc($file)
    {   
        $objReader = \PhpOffice\PhpWord\IOFactory::createReader('MsDoc');
        $FileLoad = $objReader->load(ROOT."/webroot/".$file);

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($FileLoad, 'Word2007');
        $objWriter->save(ROOT.'/webroot/front/wordCounter/dummy.docx');

        return $this->extract_docx(ROOT.'/webroot/front/wordCounter/dummy.docx');
    }

    private function extract_docx($file)
    {
        $document_content = '';
        $content = '';

        $zip = zip_open($file);

        if (!$zip || is_numeric($zip)) {
            return false;
        }

        while ($zip_entry = zip_read($zip)) {
            if (zip_entry_open($zip, $zip_entry) == false) {
                continue;
            }

            if (zip_entry_name($zip_entry) != 'word/document.xml') {
                continue;
            }

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', ' ', $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $document_content = strip_tags($content);

        return $document_content;
    } 



    public function save_contact(Request $request){

        $input=$request->except('_token');
        DB::table('contact-us')->insert($input);
        return redirect()->back()->with('message','Request sended Successfully.');


    }

    public function about_us(Request $request){
        $home_setting=DB::table('home-setting')->find(1);
        $about_page=DB::table('cmspage')->where('slug','about-us')->first();
        
        return view('cms.about_us',compact('home_setting','about_page'));

    }

    public function how_work(Request $request){
        $home_setting=DB::table('home-setting')->find(1);
        $how_work=DB::table('cmspage')->where('slug','how-work')->first();

        return view('cms.how_work',compact('home_setting','how_work'));

    }


    public function contact_us(Request $request){
        $home_setting=DB::table('home-setting')->find(1);
        $contact_us=DB::table('cmspage')->where('slug','contact-us')->first();

        return view('cms.contact_us',compact('home_setting','contact_us'));

    }


    public function terms(Request $request){
        $term=DB::table('cmspage')->where('slug','terms')->first();
        return view('cms.terms',compact('term'));

    }

    public function privacy(Request $request){
        $privacy=DB::table('cmspage')->where('slug','privacy-policy')->first();
        return view('cms.privacy',compact('privacy'));

    }

    public function faq(Request $request){
        $faqs=DB::table('faq')->take(3)->get();

        return view('cms.faq',compact('faqs'));

    }





    public function user_profile(){
  
        if($request->isMethod('post')){

        }

     

    }


    public function check_flow(Request $request,$from){


        if($request->session()->has('flow.credit')){
            session()->forget('flow.credit');
             return redirect('creadits')->with('message','Now Add Credit First');;

        }
        else if($request->session()->has('flow.job')){
            session()->forget('flow.job');
            return redirect('create-job')->with('message','Now Add Your Job');
        }
        else if($request->session()->has('flow.sub-recruiter')){
            session()->forget('flow.sub-recruiter');
            return redirect('subrecruiter')->with('message','Now Add Your Sub-Recruiter');
        }
        else{

            if($from=='payment'){
                return redirect()->back();
            }
            else if($from=='credit'){
                return redirect('creadits')->with('message','Updated Successfully.');
            }
          
            else{
            
                return redirect()->back();
            }
            
           
        }



    }


}
