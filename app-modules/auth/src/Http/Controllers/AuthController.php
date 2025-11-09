<?php

namespace Modules\Auth\Http\Controllers;


use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Enums\Providers;
use Modules\Auth\Http\Mappers\AuthMapper;
use Modules\Auth\Http\Requests\EmailPasswordLoginRequest;
use Modules\Auth\Http\Requests\UserRegisterRequest;
use Modules\Auth\Http\Responses\UserDto;
use Modules\Auth\Services\AuthService;
use Modules\Auth\Services\CookieService;

readonly class AuthController
{
    public function __construct(
        private AuthMapper    $authMapper,
        private AuthService   $authService,
        private CookieService $cookieService,
    ) {}

    public function register(UserRegisterRequest $request): UserDto
    {
        $user = $this->authMapper->toUserFromRegisterRequest($request);
        $this->authService->register($user);
        return $this->authMapper->toDto($user);
    }

    public function me(): UserDto
    {
        $user = $this->authService->findById(Auth::user()->id);
        return $this->authMapper->toDto($user);
    }

    public function loginEmailPassword(EmailPasswordLoginRequest $request): Response
    {
        $token = $this->authService->login($request->toArray(), Providers::EMAIL_PASSWORD);
        return response()->noContent()
            ->withCookie($this->cookieService->createTokenCookie($token));
    }

    public function logout(): Response
    {
        return response()->noContent()
            ->withCookie($this->cookieService->createExpiredTokenCookie());
    }
}