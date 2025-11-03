<?php

namespace Modules\Auth\Http\Mappers;

use Modules\Auth\Http\Data\PermissionData;
use Modules\Auth\Models\Permission;
use Modules\Core\Models\Course;

class PermissionMapper
{
    public function toModel(PermissionData $data): Permission
    {
        return new Permission([
            'name' => $data->name,
            'description' => $data->description,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
