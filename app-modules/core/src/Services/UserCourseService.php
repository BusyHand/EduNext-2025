<?php

namespace Modules\Core\Services;

use App\Exceptions\ModelAlreadyExistsException;
use App\Http\Data\PageableData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Core\Http\Filters\UserCourseFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\UserCourse;
use Modules\Core\Repositories\UserCourseRepository;

readonly class UserCourseService
{
    public function __construct(
        private UserCourseRepository $userCourseRepository
    )
    {
    }

    public function findAll(UserCourseFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        return $this->userCourseRepository->findAll($filterQuery, $pageableData);
    }

    public function store(User $user, Course $course): UserCourse
    {
        if ($this->userCourseRepository->existByUserIdAndCourseId($user->id, $course->id)) {
            throw new ModelAlreadyExistsException('User course already exists for this user and lesson.');
        }
        $userCourse = new UserCourse([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
        return $this->userCourseRepository->store($userCourse);
    }

    public function restore(int $userId, int $courseId): UserCourse
    {
        return $this->userCourseRepository->restore($userId, $courseId);
    }

    public function deleteSoft(User $user, Course $course): void
    {
        $userCourse = $this->userCourseRepository->findByUserIdAndCourseId($user->id, $course->id);
        $this->userCourseRepository->deleteSoft($userCourse);
    }

    public function deleteHard(User $user, Course $course): void
    {
        $userCourse = $this->userCourseRepository->findByUserIdAndCourseId($user->id, $course->id);
        $this->userCourseRepository->deleteHard($userCourse);
    }
}
