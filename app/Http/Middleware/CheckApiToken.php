<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
    public function handle(Request $request, Closure $next)
    {
        // 1. cek login session
        if (!auth()->check()) {
            return $this->unauthorized($request);
        }
        // 2. cek api_token (opsional)
        if (!session('api_token')) {
            Auth::logout(); // paksa logout
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return $this->unauthorized($request);
        }

        return $next($request);
    }
    private function unauthorized($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        return redirect()->route('login');
    }
}
