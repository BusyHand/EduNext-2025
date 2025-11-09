<?php

namespace Modules\Auth\Services;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use Modules\Auth\Security\JwtToken;
use Tymon\JWTAuth\Facades\JWTAuth;

readonly class TokenService
{
    public function createToken(?Authenticatable $user): JwtToken
    {
        if (!$user instanceof User) {
            throw new InvalidArgumentException('User must be an instance of App\Models\User');
        }

        $jwtToken = JWTAuth::fromUser($user);

        $payload = JWTAuth::setToken($jwtToken)->getPayload();
        $expireAt = Carbon::createFromTimestamp($payload->get('exp'));

        return new JwtToken(
            jwtToken: $jwtToken,
            expireAt: $expireAt
        );
    }

    public function validateToken(string $token): User
    {
        $payload = $this->decodeJwtToken($token);

        $user = User::find($payload->user_id);

        if (!$user) {
            throw new Exception('User not found');
        }

        return $user;
    }

    public function getUserFromToken(string $token): ?User
    {
        try {
            return JWTAuth::setToken($token)->authenticate();
        } catch (\Exception $e) {
            return null;
        }
    }

    public function refreshToken(string $token): string
    {
        return JWTAuth::setToken($token)->refresh();
    }

    public function invalidateToken(string $token): void
    {
        JWTAuth::setToken($token)->invalidate();
    }
}