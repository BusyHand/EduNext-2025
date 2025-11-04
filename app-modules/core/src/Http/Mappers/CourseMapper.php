<?php

namespace Modules\Core\Http\Mappers;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Http\Dtos\CourseDto;
use Modules\Core\Http\Dtos\Slims\CourseSlimDto;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\Requests\CourseFilterRequest;
use Modules\Core\Models\Course;

class CourseMapper
{

    public function toFilter(CourseFilterRequest $filterRequest): CourseFilter
    {
        return new CourseFilter($filterRequest->toCourseFilterData());
    }

    public function toModel(CourseDto $data): Course
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

    public function toDto(Course $course): CourseDto
    {
        return new CourseDto(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            is_published: $course->is_published,
            published_at: $course->published_at,
            owner_id: $course->owner_id,
            created_by: $course->created_by,
            updated_by: $course->updated_by,
            deleted_by: $course->deleted_by,
        );
    }

    public function toDtos(Collection $courses): Collection
    {
        return $courses->transform(fn($course) => $this->toDto($course));
    }

    public function toSlimDto(Course $course): CourseSlimDto
    {
        return new CourseSlimDto(
            id: $course->id,
            title: $course->title,
            description: $course->description,
            is_published: $course->is_published,
            published_at: $course->published_at,
            owner_id: $course->owner_id,
            created_by: $course->created_by,
            updated_by: $course->updated_by,
            deleted_by: $course->deleted_by,
        );
    }

    public function toSlimDtos(Collection $courses): Collection
    {
        return $courses->transform(fn($course) => $this->toSlimDto($course));
    }
}
