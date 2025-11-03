<?php

namespace Modules\Auth\Http\Mappers;

use Modules\Auth\Http\Data\UserRoleData;
use Modules\Auth\Models\UserRole;
use Modules\Core\Http\Data\CourseData;
use Modules\Core\Models\Course;

class UserRoleMapper
{
    public function toModel(UserRoleData $data): UserRole
    {
        return new UserRole([
            'id' => $data->id,
            'user_id' => $data->user_id,
            'role_id' => $data->role_id,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
