<?php

namespace App\Services\Auth;

use App\Enums\TokenAbility;
use App\Models\Administrator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TokenService
{
    /**
     * Create tokens for the given administrator.
     *
     * @return array<string, string>
     */
    public function createToken(Administrator $administrator): array
    {
        $administrator->tokens()->delete();

        $accessToken = $administrator->createToken('access_token', [TokenAbility::ACCESS_API], Carbon::now()->addMinutes(config('sanctum.access_token_expiration')));
        $refreshToken = $administrator->createToken('refresh_token', [TokenAbility::REFRESH_TOKEN], Carbon::now()->addMinutes(config('sanctum.refresh_token_expiration')));

        return [
            'access_token' => $accessToken->plainTextToken,
            'refresh_token' => $refreshToken->plainTextToken,
        ];
    }

    /**
     * Refresh the access token
     *
     * @return array<string, string>
     */
    public function refreshToken(Administrator $administrator)
    {
        $administrator->tokens()->where('name', 'access_token')->delete();
        $accessToken = $administrator->createToken('access_token', [TokenAbility::ACCESS_API->value], Carbon::now()->addMinutes(config('sanctum.access_token_expiration')));

        return [
            'access_token' => $accessToken->plainTextToken,
        ];
    }

    /**
     * Revoke the token for the currently authenticated administrator.
     */
    public function revokeToken(): void
    {
        $administrator = Auth::user();

        if ($administrator instanceof Administrator) {
            $administrator->tokens()->delete();
        }
    }
}
