<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Dtos\PaginateDto;
use Illuminate\Http\Response;
use Modules\AiIntegration\Data\AiAnswerToQuestion;
use Modules\Core\Http\Filters\Requests\LessonFilterRequest;
use Modules\Core\Http\Mappers\LessonMapper;
use Modules\Core\Http\Requests\LessonStoreRequest;
use Modules\Core\Http\Requests\LessonUpdateRequest;
use Modules\Core\Http\Requests\QuestionRequest;
use Modules\Core\Http\Response\LessonDto;
use Modules\Core\Models\Lesson;
use Modules\Core\Services\LessonService;

readonly class LessonController
{

    public function __construct(
        private LessonService $lessonService,
        private LessonMapper  $lessonMapper,
    ) {}

    public function findAll(LessonFilterRequest $filterRequest): PaginateDto
    {
        $filterQuery = $this->lessonMapper->toFilter($filterRequest);
        $paginateLessons = $this->lessonService->findAll($filterQuery, $filterRequest->toPageableData());
        return $this->lessonMapper->toPaginateSlimDtos($paginateLessons);
    }

    public function findById(Lesson $lesson): LessonDto
    {
        return $this->lessonMapper->toDto($lesson);
    }

    public function store(LessonStoreRequest $lessonRequest): LessonDto
    {
        $lessonToSave = $this->lessonMapper->toModelFromStore($lessonRequest);
        $savedLesson = $this->lessonService->store($lessonToSave);
        return $this->lessonMapper->toDto($savedLesson);
    }

    public function updatePartial(Lesson $lesson, LessonUpdateRequest $lessonRequest): LessonDto
    {
        $newLesson = $this->lessonMapper->toModelFromUpdate($lessonRequest);
        $updatedLesson = $this->lessonService->updatePartial($lesson, $newLesson);
        return $this->lessonMapper->toDto($updatedLesson);
    }

    public function askQuestion(Lesson $lesson, QuestionRequest $question): AiAnswerToQuestion
    {
        return $this->lessonService->askQuestion($lesson, $question->question);
    }

    public function restore(int $lessonId): LessonDto
    {
        $restoredLesson = $this->lessonService->restore($lessonId);
        return $this->lessonMapper->toDto($restoredLesson);
    }

    public function deleteSoft(Lesson $lesson): Response
    {
        $this->lessonService->deleteSoft($lesson);
        return response()->noContent();
    }

    public function deleteHard(Lesson $lesson): Response
    {
        $this->lessonService->deleteHard($lesson);
        return response()->noContent();
    }
}
