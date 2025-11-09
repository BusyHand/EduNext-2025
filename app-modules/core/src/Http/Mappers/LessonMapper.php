<?php

namespace Modules\Core\Http\Mappers;

use App\Http\Dtos\PaginateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Http\Filters\Requests\LessonFilterRequest;
use Modules\Core\Http\Requests\LessonStoreRequest;
use Modules\Core\Http\Requests\LessonUpdateRequest;
use Modules\Core\Http\Response\LessonDto;
use Modules\Core\Http\Response\Slims\LessonSlimDto;
use Modules\Core\Models\Lesson;

class LessonMapper
{

    public function toFilter(LessonFilterRequest $filterRequest): LessonFilter
    {
        return new LessonFilter($filterRequest->toFilterData());
    }

    public function toModelFromStore(LessonStoreRequest $data): Lesson
    {
        return new Lesson([
            'title' => $data->title,
            'content' => $data->content,
            'is_published' => $data->isPublished,
            'course_id' => $data->courseId,
        ]);
    }

    public function toModelFromUpdate(LessonUpdateRequest $data): Lesson
    {
        return new Lesson([
            'title' => $data->title,
            'content' => $data->content,
            'is_published' => $data->isPublished,
        ]);
    }

    public function toDto(Lesson $lesson): LessonDto
    {
        return new LessonDto(
            id: $lesson->id,
            title: $lesson->title,
            content: $lesson->content,
            isPublished: $lesson->is_published,
            publishedAt: $lesson->published_at,
            courseId: $lesson->course_id,
            createdBy: $lesson->created_by,
            updatedBy: $lesson->updated_by,
        );
    }

    public function toSlimDto(Lesson $lesson): LessonSlimDto
    {
        return new LessonSlimDto(
            id: $lesson->id,
            title: $lesson->title,
            content: $lesson->content,
            courseId: $lesson->course_id,
            createdAt: $lesson->created_at,
        );
    }

    public function toPaginateSlimDtos(LengthAwarePaginator $lessons): PaginateDto
    {
        return PaginateDto::toPaginateDto($lessons, fn($lesson) => $this->toSlimDto($lesson));
    }
}
