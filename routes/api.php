<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostsController;
use App\Http\Controllers\SharedLinkController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']); // Register new user
Route::post('/login', [AuthController::class, 'login']); // Login and get JWT token
Route::get('/shared-link/{shared_id}', [SharedLinkController::class, 'show']); // Public shared link access

// Protected routes (JWT token required)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']); // Logout user

    // Post routes
    Route::get('/posts', [PostsController::class, 'index']); // Get all posts
    Route::post('/posts', [PostsController::class, 'store']); // Create a new post
    Route::delete('/posts/{post_id}', [PostsController::class, 'destroy']); // Delete a post by ID
    Route::patch('/posts/{post_id}/favourite', [PostsController::class, 'toggleFavourite']); // Toggle favourite
    Route::post('/share-link', [SharedLinkController::class, 'store']); // Create new shared link
});
