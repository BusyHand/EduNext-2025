<?php

namespace Modules\Auth\Http\Middlewares;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtAuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            return $next($request);
        }
        $token = $request->cookie('access_token');
        if (!$token) {
            throw new UnauthorizedHttpException('jwt-auth', 'Access denied');
        }
        JWTAuth::setToken($token);
        $user = JWTAuth::authenticate();
        auth()->setUser($user);
        return $next($request);
    }
}