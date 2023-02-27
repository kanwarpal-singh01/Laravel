<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\BannerManagement;
use App\Model\ContentManagement;
use App\Model\Document;
use App\Model\HomeSection1;
use App\Model\HomeSection2;
use App\Model\HomeSection3;
use App\Model\HomeSection5;
use App\Model\HomeSection6;
use App\Model\KnowledgeCenter;
use App\Model\MarketPlace;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        $banner = BannerManagement::where(['status' => 1,'slug' => 'banner'])->first();
        $home_sec1 = HomeSection1::where(['status' => 1,'slug' => 'home_sec1'])->first();
        $home_sec2 = HomeSection2::where(['status' => 1,'slug' => 'home_sec2'])->orderBy('created_at', 'desc')->get();
        $home_sec5 = HomeSection5::where('status', 1)->get();
        $home_sec6 = HomeSection6::where('status', 1)->get();

        return view('front-end.home', compact('banner', 'home_sec1', 'home_sec2','home_sec5','home_sec6'));
    }

    public function documentPage(Request $request,$id){
        $doc = KnowledgeCenter::findOrFail(base64_decode($id));
        return view('front-end.document-page',compact('doc'));
    }

    public function marketPlace(Request $request){
        $market_place = MarketPlace::where('status', '1')->first();
        return view('front-end.market-place',compact('market_place'));
    }
}
