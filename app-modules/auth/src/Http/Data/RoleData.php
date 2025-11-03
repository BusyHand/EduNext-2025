<?php

namespace Modules\Auth\Http\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RoleData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $description,
        public readonly bool $is_default,
        public readonly ?int $created_by,
        public readonly ?int $updated_by,
        public readonly ?int $deleted_by,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'id'          => ['integer', 'min:1'],
            'name'        => ['string', 'max:255', 'required'],
            'description' => ['nullable', 'string'],
            'is_default'  => ['boolean'],
            'created_by'  => ['nullable', 'integer', 'min:1'],
            'updated_by'  => ['nullable', 'integer', 'min:1'],
            'deleted_by'  => ['nullable', 'integer', 'min:1'],
        ];
    }
}
