<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\KnowledgeCenter;
use Illuminate\Http\Request;

class KnowledgeCenterController extends Controller
{
    public function index(){
       $knowledge = KnowledgeCenter::where('status', 1)->get();
        return view('front-end.knowledge-center', compact('knowledge'));
    }
}
