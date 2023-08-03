<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PublicProfileController extends Controller
{
    public function viewMyProfile() {
        $user = auth()->user();
        $posts = $user->posts;

        // return inertia('')
    }
}
