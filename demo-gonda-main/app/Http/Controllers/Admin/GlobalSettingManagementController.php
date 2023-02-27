<?php

namespace App\Http\Controllers\Admin;

use App\Model\GlobalSettingManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GlobalSettingManagementController extends Controller
{
    public function edit(Request $request, $id){
        $layout_data = [
            'title' => 'General Details Management',
            'icon' => 'fa fa-cog',
        ];
        $edit = GlobalSettingManagement::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){//dd($request->all());
            $request->validate([
                'email' => 'required|email|regex:/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{3,4})$/|max:100',
                'facebook_link' => 'required|url|max:50',
                'twitter_link' => 'required|url|max:50',
                'instagram_link' => 'required|url|max:50',
                'google_plus' => 'required|url|max:50',
                'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:8|max:15',
                'address' => 'required|string',
                //'nda_summary' => 'required|string',
                //'sms_content' => 'required|string',
                //'notarize_content' => 'required|string',
                'map' => 'required|in:1,0',
                'enquiry' => 'required|in:1,0',
                //'stripe_detail' => 'sometimes|nullable|max:255',
            ]);
            $edit->email = $request['email'];
            $edit->facebook_link = $request['facebook_link'];
            $edit->twitter_link = $request['twitter_link'];
            $edit->instagram_link = $request['instagram_link'];
            $edit->google_plus = $request['google_plus'];
            $edit->mobile_number = $request['mobile_number'];
//            $edit->sms_content = $request['sms_content'];
//            $edit->notarize_content = $request['notarize_content'];
//            $edit->notarize_amount = $request['notarize_amount'];
            $edit->address = $request['address'];
            //$edit->nda_summary = $request['nda_summary'];
            $edit->map = $request['map'];
            $edit->enquiry = $request['enquiry'];
            //$edit->stripe_detail = $request['stripe_detail'];
            $edit->save();
            toastr()->success('Global Setting successfully updated!');
            return redirect()->back();
        }
        return view('admin.global-setting-management.edit', compact('layout_data', 'edit'));
    }
}
