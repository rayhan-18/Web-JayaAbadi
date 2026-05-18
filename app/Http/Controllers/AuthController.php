<?php

namespace App\Http\Controllers;

use App\Services\SupabaseService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected SupabaseService $supabase) {}

    public function register(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        $result = $this->supabase->signUp(
            $request->email,
            $request->password
        );

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']['message']], 400);
        }

        return response()->json([
            'message' => 'Register berhasil, cek email untuk verifikasi',
            'user'    => $result['user'] ?? $result,
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $result = $this->supabase->signIn(
            $request->email,
            $request->password
        );

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']['message']], 401);
        }

        return response()->json([
            'access_token'  => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
            'user'          => $result['user'],
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->auth_user);
    }

    public function logout(Request $request)
    {
        $this->supabase->signOut($request->bearerToken());

        return response()->json(['message' => 'Logout berhasil']);
    }

    public function refresh(Request $request)
    {
        $request->validate(['refresh_token' => 'required']);

        $result = $this->supabase->refreshToken($request->refresh_token);

        if (isset($result['error'])) {
            return response()->json(['message' => $result['error']['message']], 401);
        }

        return response()->json([
            'access_token'  => $result['access_token'],
            'refresh_token' => $result['refresh_token'],
        ]);
    }
}