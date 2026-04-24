<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $CheckRole): Response
    {
        if ($request->user()->role_id == $CheckRole) {
            return $next($request);
        }
        return response()->view('kosong');
    }
}
