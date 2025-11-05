<?php

namespace Modules\Core\Http\Mappers;

use Modules\Core\Http\Requests\UserLessonDto;
use Modules\Core\Models\UserLesson;

class UserLessonMapper
{
    public function toModel(UserLessonDto $data): UserLesson
    {
        return new UserLesson([
            'user_id' => $data->user_id,
            'lesson_id' => $data->lesson_id,
            'course_id' => $data->course_id,
            'progress' => $data->progress,
            'is_completed' => $data->is_completed,
            'completed_at' => $data->completed_at,
        ]);
    }
}
