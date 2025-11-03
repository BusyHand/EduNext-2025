<?php

namespace Modules\Auth\Http\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class RolePermissionData extends Data
{
    public function __construct(
        public readonly int $id,
        public readonly int $role_id,
        public readonly int $permission_id,
        public readonly ?int $created_by,
        public readonly ?int $updated_by,
        public readonly ?int $deleted_by,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'id'            => ['integer', 'min:1'],
            'role_id'       => ['integer', 'min:1', 'required'],
            'permission_id' => ['integer', 'min:1', 'required'],
            'created_by'    => ['nullable', 'integer', 'min:1'],
            'updated_by'    => ['nullable', 'integer', 'min:1'],
            'deleted_by'    => ['nullable', 'integer', 'min:1'],
        ];
    }
}
