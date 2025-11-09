<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Dtos\PaginateDto;
use App\Models\User;
use Illuminate\Http\Response;
use Modules\Core\Http\Filters\Requests\UserCourseFilterRequest;
use Modules\Core\Http\Mappers\UserCourseMapper;
use Modules\Core\Http\Response\UserCourseDto;
use Modules\Core\Models\Course;
use Modules\Core\Services\UserCourseService;

readonly class UserCourseController
{
    public function __construct(
        private UserCourseService $userCourseService,
        private UserCourseMapper  $userCourseMapper,
    ) {}

    public function findAll(UserCourseFilterRequest $filterRequest): PaginateDto
    {
        $filterQuery = $this->userCourseMapper->toFilter($filterRequest);
        $paginateUserCourses = $this->userCourseService->findAll($filterQuery, $filterRequest->toPageableData());
        return $this->userCourseMapper->toPaginateDtos($paginateUserCourses);
    }

    public function store(User $user, Course $course): UserCourseDto
    {
        $savedUserCourse = $this->userCourseService->store($user, $course);
        return $this->userCourseMapper->toDto($savedUserCourse);
    }

    public function restore(int $userId, int $courseId): UserCourseDto
    {
        $restoredLesson = $this->userCourseService->restore($userId, $courseId);
        return $this->userCourseMapper->toDto($restoredLesson);
    }

    public function deleteSoft(User $user, Course $course): Response
    {
        $this->userCourseService->deleteSoft($user, $course);
        return response()->noContent();
    }

    public function deleteHard(User $user, Course $course): Response
    {
        $this->userCourseService->deleteHard($user, $course);
        return response()->noContent();
    }
}
