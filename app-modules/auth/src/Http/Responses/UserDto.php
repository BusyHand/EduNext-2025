<?php

namespace Modules\Auth\Http\Responses;

use Spatie\LaravelData\Data;

class UserDto extends Data
{
    public function __construct(
        public readonly string $id,
        public readonly string $email,
        public readonly string $username,
        public readonly ?string $lastName,
        public readonly ?string $firstName,
        public readonly ?string $phone,
    ) {}
}