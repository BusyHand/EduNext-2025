<?php

namespace Modules\Core\Http\Mappers;

use Modules\Core\Http\Data\CourseData;
use Modules\Core\Http\Data\LessonData;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;

class LessonMapper
{
    public function toModel(LessonData $data): Lesson
    {
        return new Lesson([
            'title' => $data->title,
            'content' => $data->content,
            'is_published' => $data->is_published,
            'published_at' => $data->published_at,
            'course_id' => $data->course_id,
            'created_by' => $data->created_by,
            'updated_by' => $data->updated_by,
            'deleted_by' => $data->deleted_by,
        ]);
    }
}
