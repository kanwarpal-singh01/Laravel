<?php

namespace App\Http\Controllers\Api;

use App\Model\ContentManagement;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PagesController extends Controller
{
    public function pages(Request $request){
        try{
            $content = ContentManagement::where([['status', '1'], ['slug', $request['slug']]])->first();
            return success_response($content, 'Success');
        }
        catch (\Exception $exception){
            return error_response($exception);
        }
    }
}
