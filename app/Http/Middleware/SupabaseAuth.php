<?php

namespace App\Http\Middleware;

use App\Services\SupabaseService;
use Closure;
use Illuminate\Http\Request;

class SupabaseAuth
{
    public function __construct(protected SupabaseService $supabase) {}

    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = $this->supabase->getUser($token);

        if (isset($user['error']) || !isset($user['id'])) {
            return response()->json(['message' => 'Invalid or expired token'], 401);
        }

        // Inject user ke request
        $request->merge(['auth_user' => $user]);

        return $next($request);
    }
}