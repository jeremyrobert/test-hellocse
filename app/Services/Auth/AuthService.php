<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\Administrator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Create auth tokens for the given administrator.
     *
     * @return array<string, string>
     */
    public function createAuthTokens(Administrator $administrator): array
    {
        $accessToken = $administrator->createToken('access_token', [TokenAbility::ACCESS_API], Carbon::now()->addMinutes(config('sanctum.access_token_expiration')));
        $refreshToken = $administrator->createToken('refresh_token', [TokenAbility::REFRESH_TOKEN], Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')));

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    /**
     * Revoke the auth token for the currently authenticated administrator.
     */
    public function revokeAuthToken(): void
    {
        $administrator = Auth::user();
        if ($administrator instanceof Administrator) {
            $administrator->tokens()->where('name', 'auth_token')->delete();
        }
    }
}
