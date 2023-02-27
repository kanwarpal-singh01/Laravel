<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\UserStories;
use Illuminate\Http\Request;

class StoriesController extends Controller
{
    public function index(){
        $stories = UserStories::getAllStory();
        return view('admin.stories/storyList', compact('stories'));
    }
}

