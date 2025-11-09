<?php

namespace Modules\Auth\Security;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Facades\Hash;

class EmailPasswordUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Authenticatable
    {
        return User::with('credentials')
            ->active()
            ->find($identifier);
    }

    public function retrieveByToken($identifier, #[\SensitiveParameter] $token): ?Authenticatable
    {
        // Not implemented for remember token
        return null;
    }

    public function updateRememberToken(Authenticatable $user, #[\SensitiveParameter] $token): void
    {
        // Not implemented
    }

    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        if (!isset($credentials['email'])) {
            return null;
        }

        return User::with('credentials')
            ->where('email', $credentials['email'])
            ->active()
            ->first();
    }

    public function validateCredentials(Authenticatable $user, #[\SensitiveParameter] array $credentials): bool
    {
        if (!isset($credentials['password'])) {
            return false;
        }

        // Проверяем пароль через отношение credentials
        return $user->credentials && Hash::check($credentials['password'], $user->credentials->password);
    }

    public function rehashPasswordIfRequired(Authenticatable $user, #[\SensitiveParameter] array $credentials, bool $force = false): void
    {
        if (!$force && !Hash::needsRehash($user->getAuthPassword())) {
            return;
        }

        if (isset($credentials['password'])) {
            $user->credentials->update([
                'password' => Hash::make($credentials['password'])
            ]);
        }
    }
}