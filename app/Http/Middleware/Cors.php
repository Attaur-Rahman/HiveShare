<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = explode(',', env('ALLOWED_ORIGINS', ''));
        $origin = $request->headers->get('Origin');

        // Allow requests without Origin (Postman, server-to-server)
        $originAllowed = $origin && in_array($origin, $allowedOrigins);

        // Preflight request
        if ($request->getMethod() === 'OPTIONS') {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $originAllowed ? $origin : '')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers', '*'))
                ->header('Access-Control-Allow-Credentials', 'true');
        }

        $response = $next($request);

        if ($originAllowed) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers', '*'));
            $response->headers->set('Access-Control-Allow-Credentials', 'true');
        }

        return $response;
    }
}
