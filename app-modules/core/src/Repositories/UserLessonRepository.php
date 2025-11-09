<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Http\Filters\UserCourseFilter;
use Modules\Core\Http\Filters\UserLessonFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\UserCourse;
use Modules\Core\Models\UserLesson;

class UserLessonRepository
{
    use Pageable;

    public function findAll(UserLessonFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        $query = $filterQuery->apply(UserLesson::query());
        return $this->paginate($query, $pageableData);
    }

    public function findByUserIdAndLessonId(int $userId, int $lessonId): UserLesson
    {
        return UserLesson::where('user_id', '=', $userId)
            ->where('lesson_id', '=', $lessonId)
            ->firstOrFail();
    }

    public function existByUserIdAndLessonId(int $userId, int $lessonId): bool
    {
        return UserLesson::where('user_id', '=', $userId)
            ->where('lesson_id', '=', $lessonId)
            ->exists();
    }
    public function store(UserLesson $userLessonToSave): UserLesson
    {
        $userLessonToSave->save();
        return $userLessonToSave;
    }

    public function restore(int $userId, int $lessonId): UserLesson
    {
        $affected = UserLesson::withTrashed()
            ->where('user_id', $userId)
            ->where('lesson_id', $lessonId)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
            ]);

        if ($affected === 0) {
            throw new ModelNotFoundException("User with course not found or already restored");
        }

        return $this->findByUserIdAndLessonId($userId, $lessonId);
    }

    public function deleteSoft(UserLesson $userCourse): bool
    {
        return $userCourse->delete();
    }

    public function deleteHard(UserLesson $userCourse): bool
    {
        return $userCourse->forceDelete();
    }
}
