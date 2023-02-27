<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\ContactUs;
use App\Model\GlobalSettingManagement;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(){
        $global_setting = GlobalSettingManagement::first();
        return view('front-end.contact-us', compact('global_setting'));
    }

    public function add(Request $request){
        if ($request->isMethod('post')){
            $this->validate($request,[
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => 'required|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|max:100',
                'mobile_number' => 'required|digits_between:7,15',
                'message' => 'required|string',
            ]);
            $contact = new ContactUs();
            $contact->first_name = $request['first_name'];
            $contact->last_name = $request['last_name'];
            $contact->email = $request['email'];
            $contact->mobile_number = $request['mobile_number'];
            $contact->message = $request['message'];
            $contact->save();
            toastr()->success('Your message successfully submitted!');
            return redirect()->back();
        }
    }
}
