<?php

namespace Modules\Core\Services;

use App\Exceptions\ModelAlreadyExistsException;
use App\Http\Data\PageableData;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Filters\UserLessonFilter;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\UserLesson;
use Modules\Core\Repositories\UserLessonRepository;

readonly class UserLessonService
{
    public function __construct(
        private UserLessonRepository $userLessonRepository
    ) {}

    public function findAll(UserLessonFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        return $this->userLessonRepository->findAll($filterQuery, $pageableData);
    }

    public function store(User $user, Lesson $lesson): UserLesson
    {
        if ($this->userLessonRepository->existByUserIdAndLessonId($user->id, $lesson->id)) {
            throw new ModelAlreadyExistsException('User lesson already exists for this user and lesson.');
        }
        $userCourse = new UserLesson([
            'user_id' => $user->id,
            'course_id' => $lesson->course_id,
            'lesson_id' => $lesson->id,
        ]);
        return $this->userLessonRepository->store($userCourse);
    }

    public function complete(Lesson $lesson): UserLesson
    {
        $userLesson = $this->userLessonRepository->findByUserIdAndLessonId(Auth::id(), $lesson->id);
        $userLesson->is_completed = true;
        $userLesson->update();
        return $userLesson;
    }

    public function restore(int $userId, int $lessonId): UserLesson
    {
        return $this->userLessonRepository->restore($userId, $lessonId);
    }

    public function deleteSoft(User $user, Lesson $lesson): void
    {
        $userLesson = $this->userLessonRepository->findByUserIdAndLessonId($user->id, $lesson->id);
        $this->userLessonRepository->deleteSoft($userLesson);
    }

    public function deleteHard(User $user, Lesson $lesson): void
    {
        $userLesson = $this->userLessonRepository->findByUserIdAndLessonId($user->id, $lesson->id);
        $this->userLessonRepository->deleteHard($userLesson);
    }
}
