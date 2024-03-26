<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\V1\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Administrator;
use App\Services\Auth\TokenService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Tag(
 *     name="Authentification",
 *     description="Group of endpoints for authentification."
 * )
 */
class AuthController extends Controller
{
    public function __construct(private TokenService $tokenService) {}

    /**
     * @OA\Post(
     *     path="/api/auth/register",
     *     tags={"Authentification"},
     *     summary="Register a new administrator",
     *     description="Register a new administrator and return an access token and a refresh token.",
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/RegisterRequest")
     *     ),
     *
     *     @OA\Response(response=201, description="Successful registration"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     * )
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $administrator = Administrator::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $tokens = $this->tokenService->createToken($administrator);

        return response()->json($tokens, 201);
    }

    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Authentification"},
     *     summary="Log in as administrator",
     *     description="Log in an administrator and return an access token and a refresh token.",
     *
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/LoginRequest")
     *     ),
     *
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     * )
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
    /**
     * @OA\Get(
     *     path="/api/auth/refresh-token",
     *     tags={"Authentification"},
     *     summary="Refresh the access token",
     *     description="Refresh and return the access token.",
     *     security={"sanctum": {}},
     *
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=422, description="Unprocessable Content"),
     * )
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
