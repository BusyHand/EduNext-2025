<?php

namespace Modules\Auth\Http\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserCredentialData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $user_id,
        public readonly ?string $password,
        public readonly ?int $created_by,
        public readonly ?int $updated_by,
        public readonly ?int $deleted_by,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'id'         => ['integer', 'min:1'],
            'user_id'    => ['integer', 'min:1', 'required'],
            'password'   => ['nullable', 'string', 'min:8'],
            'created_by' => ['nullable', 'integer', 'min:1'],
            'updated_by' => ['nullable', 'integer', 'min:1'],
            'deleted_by' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
