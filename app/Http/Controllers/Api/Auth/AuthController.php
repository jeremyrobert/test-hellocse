<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Administrator;
use App\Services\Auth\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private TokenService $tokenService) {}

    /**
     * Register a new administrator and return an access token and a refresh token.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $administrator = Administrator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $tokens = $this->tokenService->createToken($administrator);

        return response()->json($tokens);
    }

    /**
     * Log in an administrator and return an access token and a refresh token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $request->authenticate();

        $administrator = Auth::user();

        if ($administrator) {
            $tokens = $this->tokenService->createToken($administrator);

            return response()->json($tokens);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Refresh the access token.
     */
    public function refreshToken(): JsonResponse
    {
        $administrator = Auth::user();

        if ($administrator) {
            $token = $this->tokenService->refreshToken($administrator);

            return response()->json($token);
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    /**
     * Log out an administrator (Revoke tokens).
     */
    public function logout(): void
    {
        $this->tokenService->revokeToken();
    }
}
