<?php

namespace Modules\Auth\Http\Mappers;

use Modules\Auth\Http\Data\RolePermissionData;
use Modules\Auth\Models\RolePermission;
use Modules\Core\Http\Data\CourseData;
use Modules\Core\Models\Course;

class RolePermissionMapper
{
    public function toModel(RolePermissionData $data): RolePermission
    {
        return new RolePermission([
            'role_id' => $data->role_id,
            'permission_id' => $data->permission_id,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
