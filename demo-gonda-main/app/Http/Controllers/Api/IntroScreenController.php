<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\IntroScreen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class IntroScreenController extends Controller
{
    public function introScreen(Request $request){
       $intro_screen_1 = IntroScreen::where('slug', 'intro_screen_1')->get();
       $intro_screen_2 = IntroScreen::where('slug', 'intro_screen_2')->get();
       //$intro_screen_3 = IntroScreen::where('slug', 'intro_screen_3')->get();
       //$intro_screen_4 = IntroScreen::where('slug', 'intro_screen_4')->get();
        $intro_screen_data_1 = [];
        $intro_screen_data_2 = [];
        //$intro_screen_data_3 = [];
        //$intro_screen_data_4 = [];
        if ($intro_screen_1->isNotEmpty()){
            foreach ($intro_screen_1 as $data_intro_screen_1){
                $intro_screen_data_1 [] = $data_intro_screen_1;
            }
        }
       if ($intro_screen_2->isNotEmpty()){
           foreach ($intro_screen_2 as $data_intro_screen_2){
               $intro_screen_data_2 [] = $data_intro_screen_2;
           }
       }
//        if ($intro_screen_3->isNotEmpty()){
//            foreach ($intro_screen_3 as $data_intro_screen_3){
//                $intro_screen_data_3 [] = $data_intro_screen_3;
//            }
//        }
//        if ($intro_screen_4->isNotEmpty()){
//            foreach ($intro_screen_4 as $data_intro_screen_4){
//                $intro_screen_data_4 [] = $data_intro_screen_4;
//            }
//        }

        $data = [
            'intro_screen_1' => $intro_screen_data_1,
            'intro_screen_2' => $intro_screen_data_2,
           //'intro_screen_3' => $intro_screen_data_3,
            //'intro_screen_4' => $intro_screen_data_4,
        ];

        return response()->json([
            'status' => 1,
           'message' => 'Intro Screen data',
            'image_path' => URL::to('/').'/public/storage/uploads/intro-screen/',
           'data' => $data,
        ]);
    }
}
