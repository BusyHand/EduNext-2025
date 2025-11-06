<?php

namespace Modules\Core\Http\Mappers;

use App\Http\Dtos\PagebleDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Requests\CourseFilterRequest;
use Modules\Core\Http\Requests\CourseStoreRequest;
use Modules\Core\Http\Requests\CourseUpdateRequest;
use Modules\Core\Http\Response\CourseDto;
use Modules\Core\Http\Response\Slims\CourseSlimDto;
use Modules\Core\Models\Course;

class CourseMapper
{

    public function toFilter(CourseFilterRequest $filterRequest): CourseFilter
    {
        return new CourseFilter($filterRequest->toCourseFilterData());
    }

    public function toModelFromStore(CourseStoreRequest $data): Course
    {
        return new Course([
            'title' => $data->title,
            'description' => $data->description,
            'is_published' => $data->isPublished,
        ]);
    }
    public function toModelFromUpdate(CourseUpdateRequest $data): Course
    {
        return new Course([
            'title' => $data->title,
            'description' => $data->description,
            'is_published' => $data->isPublished,
        ]);
    }

    public function toDto(Course $course): CourseDto
    {
        return new CourseDto(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            isPublished: $course->is_published,
            publishedAt: $course->published_at,
            ownerId: $course->owner_id,
            createdBy: $course->created_by,
            updatedBy: $course->updated_by,
        );
    }

    public function toSlimDto(Course $course): CourseSlimDto
    {
        return new CourseSlimDto(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            ownerId: $course->owner_id,
            createdAt: $course->created_at,
        );
    }

    public function toPaginateSlimDtos(LengthAwarePaginator $courses): PagebleDto
    {
        return PagebleDto::fromPaginator($courses, fn($course) => $this->toSlimDto($course));
    }
}
