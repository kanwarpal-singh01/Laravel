<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Contact;
use App\Model\Document;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class DocumentController extends Controller
{
    public function list(){
        $layout_data = [
            'title' => "Document Management",
            'url' => route('admin.document.list'),
            'icon' => "fa fa-users",
        ];
        $list = Document::with('user', 'contact.userByMobileNumber', 'stateUs', 'templateData')
                            ->orderBy('created_at', 'desc')
                            ->paginate(50);

        return view('admin.document.list', compact('layout_data','list'));
    }

    public function view(Request $request, $id){
        $layout_data = [
            'title' => "Document Management",
            'url' => route('admin.document.list'),
            'icon' => "fa fa-users",
        ];
        $view = Document::with('user', 'contact', 'stateUs', 'templateData')->findOrFail(base64_decode($id));
        return view('admin.document.view', compact('view', 'layout_data'));
    }

    public function checkSecurityPin(Request $request){
        if ($request->isMethod('post')){
            $request->validate([
                'secret_pin' => 'required|min:4|max:4|confirmed',
                'secret_pin_confirmation' => 'required',
            ]);
            $user = User::where('secret_pin', $request['secret_pin'])->first();
            if ($user){
                $doc = Document::Where('id', $request['document_id'])->first();
                if (!empty($doc->pdf)){
                    return redirect(asset('public/storage/uploads/pdf/'.$doc->pdf));
                }
                toastr()->error('No NDA Found');
                return redirect()->back();
            }
            toastr()->error('You entered wrong secret pin');
            return redirect()->back();
        }
    }
}
