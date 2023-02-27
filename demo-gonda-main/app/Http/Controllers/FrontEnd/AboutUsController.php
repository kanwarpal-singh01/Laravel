<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\ContentManagement;
use App\Model\HomeSection1;
use Illuminate\Http\Request;

class AboutUsController extends Controller
{
    public function index(){
       $lorem_about = HomeSection1::where(['status' => 1, 'slug' => 'home_sec1'])->first();
       $mission_about = HomeSection1::where(['status' => 1, 'slug' => 'about_mission'])->first();
       $mission_des_about = HomeSection1::where(['status' => 1, 'slug' => 'mission_des_about'])->first();
        return view('front-end.about-us', compact('lorem_about', 'mission_about', 'mission_des_about'));
    }
}
