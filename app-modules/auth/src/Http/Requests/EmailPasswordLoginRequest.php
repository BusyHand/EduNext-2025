<?php

namespace Modules\Auth\Http\Requests;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class EmailPasswordLoginRequest extends Data
{

    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'email' => ['required', 'string', 'max:255',],
            'password' => ['required', 'string', 'max:500'],
        ];
    }

}