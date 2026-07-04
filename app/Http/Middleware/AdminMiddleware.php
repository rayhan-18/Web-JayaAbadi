<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'superadmin'])) {
        return $next($request);
        }

        return $next($request);
    }
}