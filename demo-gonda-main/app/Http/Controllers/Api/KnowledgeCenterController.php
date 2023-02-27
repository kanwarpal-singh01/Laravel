<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\KnowledgeCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class KnowledgeCenterController extends Controller
{
    public function list(Request $request){
        $knowledge = KnowledgeCenter::where('status', 1)->get();
          if ($knowledge->isNotEmpty()){
              return response()->json([
                  'status' => 1,
                  'message' => 'Knowledge Center List.',
                  'document_path' => URL::to('/').'/public/storage/uploads/knowledge-center/',
                  'data' => $knowledge
              ]);
          }
        return response()->json([
            'status' => 1,
            'message' => 'No Records',
            'data' => []
        ]);
    }
}
