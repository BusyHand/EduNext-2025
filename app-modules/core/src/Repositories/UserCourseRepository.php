<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Http\Filters\UserCourseFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\UserCourse;
use Modules\Core\Models\UserLesson;

class UserCourseRepository
{
    use Pageable;

    public function findAll(UserCourseFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        $query = $filterQuery->apply(UserCourse::query());
        return $this->paginate($query, $pageableData);
    }

    public function findByUserIdAndCourseId(int $userId, int $courseId): UserCourse
    {
        return UserCourse::where('user_id', '=', $userId)
            ->where('course_id', '=', $courseId)
            ->firstOrFail();
    }

    public function existByUserIdAndCourseId(int $userId, int $courseId): bool
    {
        return UserCourse::where('user_id', '=', $userId)
            ->where('course_id', '=', $courseId)
            ->exists();
    }


    public function store(UserCourse $lessonToSave): UserCourse
    {
        $lessonToSave->save();
        return $lessonToSave;
    }

    public function restore(int $userId, int $courseId): UserCourse
    {
        $affected = UserCourse::withTrashed()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
                'deleted_by' => null,
            ]);

        if ($affected === 0) {
            throw new ModelNotFoundException("User with course not found or already restored");
        }

        return $this->findByUserIdAndCourseId($userId, $courseId);
    }

    public function deleteSoft(UserCourse $userCourse): bool
    {
        return $userCourse->delete();
    }

    public function deleteHard(UserCourse $userCourse): bool
    {
        return $userCourse->forceDelete();
    }

}
