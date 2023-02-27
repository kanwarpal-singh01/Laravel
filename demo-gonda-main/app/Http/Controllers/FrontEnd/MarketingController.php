<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Model\MarketingAdManagement;
use Illuminate\Http\Request;

class MarketingController extends Controller
{
    public function add(Request $request){
        $request->validate([
            'describe_id' => 'required',
            'email' => 'required|email|max:255',
        ]);
        $marketing = new MarketingAdManagement();
        $marketing->describe_id = $request['describe_id'];
        $marketing->email = $request['email'];
        $marketing->save();
        toastr()->success('Your information has been submitted.');
        return redirect()->route('home');
    }
}
