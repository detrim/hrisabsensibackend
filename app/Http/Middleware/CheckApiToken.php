<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = session('api_token');
        if (!$token) {
            // kalau request AJAX / API return JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            // kalau web redirect login
            return redirect()->route('login');
        }
        return $next($request);
    }
}
