<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class LikeController extends Controller
{
    public function like(Request $request, Post $post)
    {
        $user = $request->user();

        // Check if the user has already liked the post
        if (!$user->likes->contains('post_id', $post->id)) {
            $user->likes()->create(['post_id' => $post->id]);
        }

        return back()->banner('Post liked successfully');
    }

    public function unlike(Request $request, Post $post)
    {
        $user = $request->user();

        // Check if the user has liked the post
        if ($user->likes->contains('post_id', $post->id)) {
            $user->likes()->where('post_id', $post->id)->delete();
        }

        return back()->banner('You have unliked this post.');
    }
    
}
