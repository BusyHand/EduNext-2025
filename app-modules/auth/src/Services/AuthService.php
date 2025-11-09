<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\NewAccessToken;
use Modules\Auth\Security\JwtToken;

readonly class AuthService
{
    public function __construct(
        private TokenService $tokenService
    ) {}

    public function register(User $user): User
    {
        return DB::transaction(function () use ($user) {
            $user->save();
            $user->credentials->user_id = $user->id;
            $user->credentials->password = Hash::make($user->credentials->password);
            $user->credentials->save();
            $user->assignRole('student'); // или другая роль по умолчанию
            return $user;
        });
    }

    public function findById(int $id): User
    {
        return User::whereId($id)
            ->firstOrFail();
    }

    public function login(array $credentials, string $provider): JwtToken
    {
        $guard = Auth::guard($provider);
        if (!$guard->attempt($credentials)) {
            throw new AuthenticationException('Unauthenticated', [$provider],);
        }
        return $this->tokenService->createToken($guard->user());
    }
}