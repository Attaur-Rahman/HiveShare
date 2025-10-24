<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all posts with user info
        $posts = Post::where('user_id', Auth::user()->user_id) // filter by logged-in user
            ->select('post_id', 'platform', 'post_url') // only needed columns
            ->orderBy('created_at', 'desc') // latest posts first
            ->get();

        if ($posts->isEmpty()) {
            return response()->json([
                'message' => 'No posts found'
            ], 404); // use 404 for not found
        }

        return response()->json([
            'message' => 'All posts fetched successfully',
            'posts' => $posts,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|in:Instagram,Twitter,Youtube,Facebook,LinkedIn',
            'post_url' => 'required|url|max:2083',
            'is_favourite' => 'sometimes|boolean',
            'is_shared' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $post = Post::create([
            'platform' => $request->platform,
            'post_url' => $request->post_url,
            'user_id' => $request->user()->user_id, // from JWT user
            'is_favourite' => false,
            'is_shared' => false,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post_id' => $post->post_id
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($post_id)
    {
        // Find the post for the logged-in user
        $post = Post::where('post_id', $post_id)
            ->where('user_id', Auth::user()->user_id)
            ->first();

        if (!$post) {
            return response()->json([
                'message' => 'Post not found or you are not authorized to delete it.'
            ], 404);
        }

        // Delete the post
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully'
        ]);
    }
}
