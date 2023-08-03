<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userRole = auth()->user()->roles->pluck('name');

        if($userRole->contains('user')) {
            $posts = Post::with(['likes', 'user'])
                        ->where('privacy', 'public')
                        ->where('hidden', 0)
                        ->get();
        } else {
            $posts = Post::with(['likes', 'user'])
                        ->where('privacy', 'public')
                        ->get();
        }
        return inertia('Post/Index', [
            'posts' => $posts
        ]);
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
        
        return inertia('Post/Search', [
            'posts' => $posts
        ]);
    }

    public function hidePost(Post $post)
    {
        $post->hidden = !$post->hidden;
        $post->save();

        if($post->hidden == 1) {
            $recipient = $post->user->email;
            $content = "Your post has been hid by an admin because it violates our community guidelines. 
                        If you think it didn't contain anything that violates our rules, 
                        please email us to bring back your post. Thank you!";
            $subject = "Post Update";

            Mail::send('emails.notify', ['content' => $content], function($message) use ($subject, $recipient) {
                $message->to($recipient);
                $message->subject($subject);
            });


            return back()->banner('This post has been hidden and the user has been notified.');
        }

        return back()->banner('Post visibility has been changed.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $post = [
            'user_id' => auth()->user()->id,
            'content' => $request->content
        ];

        // dd($post);
        $fileName = null;

        if($request->photo) {
            $fileName = time().'.'.$request->photo->extension();
            $request->photo->move(public_path('images/photos'), $fileName);
            $post['photo'] = $fileName;
        }

        Post::create($post);

        return back()->banner('Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // dd($post->user);
        return inertia('Post/Edit', [
            'post' => $post,
            'user' => $post->user
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        if(!$request->photo){
            $post->photo = null;
            $post->content = $request->content;
            $post->save();
        } else {
            $post->content = $request->content;
            $post->save();
        }

        return back()->banner('Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        if($post->user_id == auth()->user()->id){
            $post->delete();
        }

        return back()->banner('Your post has been deleted.');
    }

    public function delete(Post $post)
    {
        if($post->user_id == auth()->user()->id){
            $post->delete();
        }

        return redirect(route('home'))->banner('Your post has been deleted.');
    }
}
