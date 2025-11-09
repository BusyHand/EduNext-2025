<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Dtos\PaginateDto;
use App\Models\User;
use Illuminate\Http\Response;
use Modules\Core\Http\Filters\Requests\UserLessonFilterRequest;
use Modules\Core\Http\Mappers\UserLessonMapper;
use Modules\Core\Http\Response\UserLessonDto;
use Modules\Core\Models\Lesson;
use Modules\Core\Services\UserLessonService;

readonly class UserLessonController
{

    public function __construct(
        private UserLessonService $userLessonService,
        private UserLessonMapper  $userLessonMapper,
    ) {}

    public function findAll(UserLessonFilterRequest $filterRequest): PaginateDto
    {
        $filterQuery = $this->userLessonMapper->toFilter($filterRequest);
        $paginateUserLessons = $this->userLessonService->findAll($filterQuery, $filterRequest->toPageableData());
        return $this->userLessonMapper->toPaginateDtos($paginateUserLessons);
    }

    public function store(User $user, Lesson $lesson): UserLessonDto
    {
        $savedUserLesson = $this->userLessonService->store($user, $lesson);
        return $this->userLessonMapper->toDto($savedUserLesson);
    }

    public function complete(Lesson $lesson): UserLessonDto {
        $savedUserLesson = $this->userLessonService->complete($lesson);
        return $this->userLessonMapper->toDto($savedUserLesson);
    }

    public function restore(int $userId, int $lessonId): UserLessonDto
    {
        $restoredLesson = $this->userLessonService->restore($userId, $lessonId);
        return $this->userLessonMapper->toDto($restoredLesson);
    }

    public function deleteSoft(User $user, Lesson $lesson): Response
    {
        $this->userLessonService->deleteSoft($user, $lesson);
        return response()->noContent();
    }

    public function deleteHard(User $user, Lesson $lesson): Response
    {
        $this->userLessonService->deleteHard($user, $lesson);
        return response()->noContent();
    }
}
