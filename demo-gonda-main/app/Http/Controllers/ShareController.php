<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function share(Request $request, $id){
        $document_id = $id;
        return view('share', compact('document_id'));
    }
}
