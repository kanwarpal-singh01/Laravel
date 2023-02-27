<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => 'ContactUs Management',
            'url' => route('admin.contact.list'),
            'icon' => 'fa fa-address-book'
        ];
        $list= ContactUs::orderBy('created_at', 'desc')->paginate(config('custom-paginate.paginate.number'));
        return view('admin.contact-us.list', compact('layout_data','list'));
    }

    public function reply(Request $request, $id, $page=null){
        $layout_data = [
            'title' => 'ContactUs Management',
            'url' => route('admin.contact.list'),
            'icon' => 'fa fa-address-book'
        ];
        $contact = ContactUs::findOrFail(base64_decode($id));
        if ($request->isMethod('post')){
            $request->validate([
               'email' => 'required|exists:contact_us',
               'from_email' => 'required|email|max:100',
               'subject' => 'required|string|max:100',
               'message' => 'required|string',
            ]);
            $to_email = $request['email'];
            $from_email = $request['from_email'];
            $message = $request['message'];
            $subject = $request['subject'];
            $data['msg'] = $message;
            Mail::send('admin.contact-us.reply-mail',$data, function ($query) use ($to_email, $from_email, $subject)
            {
                $query->to($to_email)->from($from_email)->subject($subject);
            });
            toastr()->success('Successfully Mail Send to customer');
            return redirect('Kobe/contact-us/list?page='.$page);
        }
        return view('admin.contact-us.reply', compact('layout_data','contact'));
    }
}
