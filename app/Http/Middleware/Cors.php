<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    protected $allowedOrigin = 'https://hiveshare.vercel.app';

    public function handle(Request $request, Closure $next)
    {
        $origin = $request->headers->get('Origin');

        // Allow only the specified origin
        if ($origin !== $this->allowedOrigin) {
            return response()->json(['message' => 'CORS: Origin not allowed'], 403);
        }

        // Preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $this->allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // Normal request
        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', $this->allowedOrigin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
