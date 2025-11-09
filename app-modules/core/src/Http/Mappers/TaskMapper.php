<?php

namespace Modules\Core\Http\Mappers;

use App\Http\Dtos\PaginateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\Requests\CourseFilterRequest;
use Modules\Core\Http\Filters\Requests\TaskFilterRequest;
use Modules\Core\Http\Filters\TaskFilter;
use Modules\Core\Http\Requests\CourseStoreRequest;
use Modules\Core\Http\Requests\CourseUpdateRequest;
use Modules\Core\Http\Response\CourseDto;
use Modules\Core\Http\Response\Slims\CourseSlimDto;
use Modules\Core\Http\Response\TaskDto;
use Modules\Core\Models\Course;
use Modules\Core\Models\Task;

class TaskMapper
{

    public function toFilter(TaskFilterRequest $filterRequest): TaskFilter
    {
        return new TaskFilter($filterRequest->toFilterData());
    }

    public function toDto(Task $task): TaskDto
    {
        return new TaskDto(
            id: $task->id,
            userId: $task->user_id,
            lessonId: $task->lesson_id,
            courseId: $task->course_id,
            status: $task->status->name,
            createdAt: $task->created_at,
            content: $task->content,
            feedback: $task->feedback,
            lastAnswer: $task->last_answer,
        );
    }

    public function toPaginateDtos(LengthAwarePaginator $tasks): PaginateDto
    {
        return PaginateDto::toPaginateDto($tasks, fn($task) => $this->toDto($task));
    }
}
