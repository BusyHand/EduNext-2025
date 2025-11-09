<?php

namespace Modules\Auth\Services;

use Modules\Auth\Security\JwtToken;
use Symfony\Component\HttpFoundation\Cookie;

class CookieService
{
    public function createTokenCookie(JwtToken $jwtToken): Cookie
    {
        return cookie(
            'access_token',
            $jwtToken->jwtToken,
            now()->diffInMinutes(($jwtToken->expireAt)),
            '/',
            null,
            config('app.env') === 'production',
            true,
            false,
            'strict'
        );
    }

    public function createExpiredTokenCookie(): Cookie
    {
        return cookie()->forget('access_token');
    }
}