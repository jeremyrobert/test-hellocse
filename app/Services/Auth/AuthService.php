<?php

namespace App\Services\Auth;

use App\Models\Administrator;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    /**
     * Create an auth token for the given administrator.
     */
    public function createAuthToken(Administrator $administrator): string
    {
        $administrator->tokens()->where('name', 'auth_token')->delete();

        return $administrator->createToken('auth_token')->plainTextToken;
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
