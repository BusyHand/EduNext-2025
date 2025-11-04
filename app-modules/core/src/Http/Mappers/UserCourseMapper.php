<?php

namespace Modules\Core\Http\Mappers;

use Modules\Core\Http\Dtos\UserCourseDto;
use Modules\Core\Models\UserCourse;

class UserCourseMapper
{
    public function toModel(UserCourseDto $data): UserCourse
    {
        return new UserCourse([
            'user_id' => $data->user_id,
            'course_id' => $data->course_id,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
