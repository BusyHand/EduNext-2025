<?php

namespace Modules\Core\Http\Mappers;

use App\Http\Dtos\PaginateDto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\Requests\UserCourseFilterRequest;
use Modules\Core\Http\Filters\UserCourseFilter;
use Modules\Core\Http\Response\UserCourseDto;
use Modules\Core\Models\UserCourse;

class UserCourseMapper
{
    public function toFilter(UserCourseFilterRequest $filterRequest): UserCourseFilter
    {
        return new UserCourseFilter($filterRequest->toFilterData());
    }

    public function toDto(UserCourse $userCourse): UserCourseDto
    {
        return new UserCourseDto(
            id: $userCourse->id,
            userId: $userCourse->user_id,
            courseId: $userCourse->course_id,
        );
    }

    public function toPaginateDtos(LengthAwarePaginator $userCourses): PaginateDto
    {
        return PaginateDto::toPaginateDto($userCourses, fn($userCourse) => $this->toDto($userCourse));
    }
}
