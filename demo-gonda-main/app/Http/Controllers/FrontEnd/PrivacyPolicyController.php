<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\ContentManagement;
use App\Model\HomeSection6;
use Illuminate\Http\Request;

class PrivacyPolicyController extends Controller
{
    public function index(){
        $privacy_policy = ContentManagement::where(['status' => 1, 'slug' => 'privacy_policy'])->first();
        return view('front-end.privacy-policy', compact('privacy_policy'));
    }
}
