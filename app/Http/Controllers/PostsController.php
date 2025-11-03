<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    // Get all posts for the logged-in user
    public function index()
    {
        $posts = Post::where('user_id', Auth::user()->user_id) // Filter by logged-in user
            ->select('post_id', 'platform', 'url', 'title', 'description', 'is_favourite') // Select required columns
            ->orderBy('created_at', 'desc') // Latest posts first
            ->get();

        if ($posts->isEmpty()) { // If no posts found
            return response()->json([
                'message' => 'No posts found'
            ], 404);
        }

        return response()->json([
            'message' => 'All posts fetched successfully',
            'posts' => $posts,
        ], 200);
    }

    // Store a new post for the logged-in user
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|in:Instagram,Twitter,Youtube,LinkedIn', // Valid platforms
            'url' => 'required|string', // Valid URL
            'title' => 'required|string|max:255', // Required title
            'description' => 'nullable|string', // Optional description
            'is_favourite' => 'sometimes|boolean', // Optional boolean
        ]);

        if ($validator->fails()) { // Validation failed
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::create([
            'platform' => $request->platform, // Platform name
            'url' => $request->url, // Post URL
            'title' => $request->title, // Title
            'description' => $request->description, // Description
            'user_id' => $request->user()->user_id, // Logged-in user ID
            'is_favourite' => $request->is_favourite ?? false, // Default false
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post_id' => $post->post_id
        ], 201);
    }

    // Delete a post by post_id for the logged-in user
    public function destroy($post_id)
    {
        $post = Post::where('post_id', $post_id) // Find post by ID
            ->where('user_id', Auth::user()->user_id) // Match with logged-in user
            ->first();

        if (!$post) { // Post not found or unauthorized
            return response()->json([
                'message' => 'Post not found or you are not authorized to delete it.'
            ], 404);
        }

        $post->delete(); // Delete post

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }

    // Toggle route for favourite posts
    public function toggleFavourite($post_id)
    {
        // Find the post owned by the logged-in user
        $post = Post::where('post_id', $post_id)
            ->where('user_id', Auth::user()->user_id)
            ->first();

        if (!$post) {
            return response()->json([
                'message' => 'Post not found or unauthorized'
            ], 404);
        }

        // Toggle the is_favourite value
        $post->is_favourite = !$post->is_favourite;
        $post->save();

        return response()->json([
            'message' => $post->is_favourite
                ? 'Added to favourites'
                : 'Removed from favourites',
            'is_favourite' => $post->is_favourite
        ], 200);
    }
}
