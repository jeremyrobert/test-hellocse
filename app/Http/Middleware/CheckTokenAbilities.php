<?php

namespace App\Http\Middleware;

use App\Enums\TokenAbility;
use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenAbilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $currentUser = $request->user()) {
            throw new AuthenticationException;
        }

        if (! $currentUser->tokenCan(TokenAbility::ACCESS_API->value)) {
            throw new AuthenticationException;
        }

        return $next($request);
    }
}
