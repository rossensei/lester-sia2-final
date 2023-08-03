<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class SearchController extends Controller
{
    public function index()
    {
        return inertia('Search/Index');
    }
    public function search($searchKey)
    {
        $userRole = auth()->user()->roles->pluck('name');

        if($userRole->contains('user')) {
            $posts = Post::with(['likes', 'user'])
                        ->where('content', 'like', "%".$searchKey."%")
                        ->where('privacy', 'public')
                        ->where('hidden', 0)
                        ->get();
        } else {
            $posts = Post::with(['likes', 'user'])
                        ->where('content', 'like', "%".$searchKey."%")
                        ->get();
        }
        return inertia('Search/Result', [
            'posts' => $posts
        ]);
    }
}
