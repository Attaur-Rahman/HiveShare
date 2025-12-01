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

        // Block non-allowed origins with standard 403 error
        if ($origin !== $this->allowedOrigin) {
            abort(403, 'CORS: Origin not allowed');
        }

        // Preflight OPTIONS request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $this->allowedOrigin)
                ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', '*')
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        // Normal request
        $response = $next($request);

        return $response
            ->header('Access-Control-Allow-Origin', $this->allowedOrigin)
            ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', '*')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
