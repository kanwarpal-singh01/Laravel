<?php

namespace App\Http\Controllers\Admin;

use App\Model\Category;
use App\Http\Controllers\Controller;
use App\Model\ContactUs;
use App\Model\Document;
use App\Model\ManageChallenge;
use App\Model\Testimonial;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(){
        $layout_data = [
            'title' => 'Dashboard',
            'url' => route('admin.dashboard'),
            'icon' => 'fa fa-home'
        ];
        $total_user = User::get();
        $total_testimonial = Testimonial::get();
        $total_challenge = ManageChallenge::get();
        $total_contact = ContactUs::get();
        //User Chart
        $users = User::select('id', 'created_at')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        $usermcount = [];
        $userArr = [];
        foreach ($users as $key => $value) {
            $usermcount[(int)$key] = count($value);
        }
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            if (!empty($usermcount[$i])) {
                $userArr[$i]['count'] = $usermcount[$i];
            } else {
                $userArr[$i]['count'] = 0;
            }
            $userArr[$i]['month'] = $month[$i - 1];
        }
        //User Chart

        //Document in draft Chart
        $document = Document::where('type', '2')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        $documentmcount = [];
        $documentArr = [];
        foreach ($document as $key => $value) {
            $documentmcount[(int)$key] = count($value);
        }
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            if (!empty($documentmcount[$i])) {
                $documentArr[$i]['count'] = $documentmcount[$i];
            } else {
                $documentArr[$i]['count'] = 0;
            }
            $documentArr[$i]['month'] = $month[$i - 1];
        }
        //Document in draft Chart

        //Document sent Chart
        $document_sent = Document::where('status', '1')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        $documentsentmcount = [];
        $documentSentArr = [];
        foreach ($document_sent as $key => $value) {
            $documentsentmcount[(int)$key] = count($value);
        }
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            if (!empty($documentsentmcount[$i])) {
                $documentSentArr[$i]['count'] = $documentsentmcount[$i];
            } else {
                $documentSentArr[$i]['count'] = 0;
            }
            $documentSentArr[$i]['month'] = $month[$i - 1];
        }
        //Document sent Chart

        //Document complete Chart
        $document_complete = Document::where('status', '3')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('m');
            });
        $documentcompletemcount = [];
        $documentCompleteArr = [];
        foreach ($document_complete as $key => $value) {
            $documentcompletemcount[(int)$key] = count($value);
        }
        $month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        for ($i = 1; $i <= 12; $i++) {
            if (!empty($documentcompletemcount[$i])) {
                $documentCompleteArr[$i]['count'] = $documentcompletemcount[$i];
            } else {
                $documentCompleteArr[$i]['count'] = 0;
            }
            $documentCompleteArr[$i]['month'] = $month[$i - 1];
        }
        //Document sent Chart
        return view('admin.dashboard', compact('layout_data', 'total_user', 'total_testimonial', 'userArr', 'total_challenge', 'total_contact', 'documentArr', 'documentSentArr', 'documentCompleteArr'));
    }
}
