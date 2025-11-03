<?php

namespace Modules\Core\Http\Mappers;

use Modules\Core\Http\Data\CourseData;
use Modules\Core\Models\Course;

class CourseMapper
{
    public function toModel(CourseData $data): Course
    {
        return new Course([
            'title' => $data->title,
            'description' => $data->description,
            'is_published' => $data->is_published,
            'published_at' => $data->published_at,
            'owner_id' => $data->owner_id,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
