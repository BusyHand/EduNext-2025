<?php

namespace Modules\Auth\Http\Mappers;

use Modules\Auth\Http\Data\RoleData;
use Modules\Auth\Models\Role;

class RoleMapper
{
    public function toModel(RoleData $data): Role
    {
        return new Role([
            'name' => $data->name,
            'description' => $data->description,
            'is_default' => $data->is_default,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
