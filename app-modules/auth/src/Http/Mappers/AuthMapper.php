<?php

namespace Modules\Auth\Http\Mappers;

use App\Models\User;
use Modules\Auth\Http\Requests\UserRegisterRequest;
use Modules\Auth\Http\Responses\UserDto;
use Modules\Auth\Models\UserCredential;

class AuthMapper
{

    public function toUserFromRegisterRequest(UserRegisterRequest $register): User
    {
        $user = new User([
            'email' => $register->email,
            'username' => $register->username,
        ]);
        $credential = new UserCredential([
            'password' => $register->password,
        ]);
        $user->setRelation('credentials', $credential);
        $credential->setRelation('user', $user);
        return $user;
    }

    public function toDto(User $user): UserDto
    {
        return new UserDto(
            id: $user->id,
            email: $user->email,
            username: $user->username,
            lastName: $user->last_name,
            firstName: $user->first_name,
            phone: $user->phone
        );
    }
}