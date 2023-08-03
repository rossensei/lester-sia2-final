<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PublicProfileController;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return Inertia::render('Dashboard');
    // })->name('dashboard');

    Route::get('/home', [PostController::class, 'index'])->name('home');
    Route::get('/explore', [SearchController::class, 'index'])->name('explore');
    Route::get('/explore/{searchKey}', [SearchController::class, 'search']);
    Route::post('/create-post', [PostController::class, 'store'])->middleware('permission:create post');
    Route::post('/like-post/{post}', [LikeController::class, 'like'])->middleware('role:user');
    Route::delete('/unlike-post/{post}', [LikeController::class, 'unlike'])->middleware('role:user');
    Route::get('/search/{searchKey}', [PostController::class, 'search']);
    Route::delete('/delete-post/{post}', [PostController::class, 'destroy'])->middleware('permission:delete post');
    Route::get('/post/edit/{post}', [PostController::class, 'edit'])->middleware('permission:edit post');
    Route::patch('/post/{post}', [PostController::class, 'update']);
    Route::post('/post/toggle/{post}', [PostController::class, 'hidePost'])->middleware('role:admin');
    // Route::get('/my-profile', [PublicProfileController::class, 'viewMyProfile'])->name('user.profile');
});
