<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Model\KnowledgeCenter;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list()
    {
        $category = Category::where('status', 1)->get();
        $category_id = [];
        if ($category->isNotEmpty()) {
            foreach ($category as $key => $category_data) {
                $category_id[] = $category_data->id;
                $knowledge_center = KnowledgeCenter::whereRaw('FIND_IN_SET(?, category_id) > 0', [$category_data->id])->get();
                if ($knowledge_center->isNotEmpty()) {
                    foreach ($knowledge_center as $k => $knowledge_center_data) {
                        $knowledge_center[$k]['coming_soon'] = !empty($knowledge_center_data->description) ? 1 : 0;
                    }
                }
                $category[$key]['knowledge_center_data'] = $knowledge_center;
            }
        }
        if ($category->isNotEmpty()) {
            return response()->json([
                'status' => 1,
                'message' => 'All Records',
                'data' => $category
            ]);
        }
        return response()->json([
            'status' => 0,
            'message' => 'No Records',
            'data' => []
        ]);
    }
}
