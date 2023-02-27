<?php

namespace App\Http\Controllers;

use App\Model\ContentManagement;
use App\Model\Footer;
use App\Model\GlobalSettingManagement;
use App\Model\HomeSection3;
use App\Model\KnowledgeCenter;
use App\Model\MenuManagement;
use App\Model\PopupManagement;
use App\Model\Testimonial;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {

        $footer = Footer::where('status', 1)->first();
        $home_sec3 = HomeSection3::where(['status' => 1,'slug' => 'home_video'])->first();
        $testimonial = Testimonial::where('status', 1)->get();
        $menu_header = MenuManagement::where([['status', 1],['menu_for', 'header']])->orderBy('serial_number')->get();
        $footer_explore = MenuManagement::where([['status', 1],['menu_for', 'footer-explore']])->orderBy('serial_number')->get();
        $footer_help = MenuManagement::where([['status', 1],['menu_for', 'footer-help']])->orderBy('serial_number')->get();
        $popup = PopupManagement::where('status', 1)->first();
        $global_setting_footer = GlobalSettingManagement::first();
        view()->share(compact('footer','home_sec3', 'testimonial', 'global_setting_footer', 'menu_header', 'footer_explore', 'footer_help', 'popup'));
    }
}
