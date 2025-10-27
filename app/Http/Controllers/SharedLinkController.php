<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\SharedLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SharedLinkController extends Controller
{
    // Store a new shared link for the logged-in user
    public function store(Request $request)
    {
        // Optional expiry time (default: 24 hours)
        $expiresAt = now()->addMinutes(2);

        // Check if the user already has a shared link
        $existingLink = SharedLink::where('user_id', Auth::user()->user_id)->first();

        // If an old link exists, delete it
        if ($existingLink) {
            $existingLink->delete();
        }

        // Create a new shared link
        $sharedLink = SharedLink::create([
            'user_id' => Auth::user()->user_id,
            'expires_at' => $expiresAt,
        ]);

        return response()->json([
            'message' => 'Shared link generated successfully',
            'expires_at' => $sharedLink->expires_at,
            'public_url' => url("/api/shared-link/{$sharedLink->shared_id}"),
        ], 201);
    }

    // Show shared link details publicly
    public function show($shared_id)
    {
        $link = SharedLink::where('shared_id', $shared_id)->first();

        if (!$link) return response()->json(['message' => 'Shared link not found'], 404);

        // Optional expiry check
        if ($link->expires_at && now()->greaterThan($link->expires_at))
            return response()->json(['message' => 'Shared link has expired'], 410);

        // Fetch post with only required fields
        $post = Post::where('user_id', $link->user_id)
            ->select('post_id', 'platform', 'url', 'title', 'description')
            ->first();

        return response()->json([
            'message' => 'Shared link fetched successfully',
            'post' => $post,
        ], 200);
    }
}
