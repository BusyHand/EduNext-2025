<?php

namespace Modules\Core\Http\Mappers;

use App\Http\Dtos\PaginateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\Requests\UserLessonFilterRequest;
use Modules\Core\Http\Filters\UserLessonFilter;
use Modules\Core\Http\Response\UserLessonDto;
use Modules\Core\Models\UserLesson;

class UserLessonMapper
{
    public function toFilter(UserLessonFilterRequest $filterRequest): UserLessonFilter
    {
        return new UserLessonFilter($filterRequest->toFilterData());
    }

    public function toDto(UserLesson $userLesson): UserLessonDto
    {
        return new UserLessonDto(
            id: $userLesson->id,
            userId: $userLesson->user_id,
            lessonId: $userLesson->lesson_id,
            courseId: $userLesson->course_id,
            progress: $userLesson->progress,
            isCompleted: $userLesson->is_completed,
            completedAt: $userLesson->completed_at,
        );
    }

    public
    function toPaginateDtos(LengthAwarePaginator $userLessons): PaginateDto
    {
        return PaginateDto::toPaginateDto($userLessons, fn($userLesson) => $this->toDto($userLesson));
    }
}
