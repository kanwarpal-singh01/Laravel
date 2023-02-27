<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\ContentManagement;
use Illuminate\Http\Request;

class TermsConditionsController extends Controller
{
    public function index(){
        $terms_conditions = ContentManagement::where(['status' => 1, 'slug' => 'terms_conditions'])->first();
        return view('front-end.terms-conditions', compact('terms_conditions'));
    }
}
