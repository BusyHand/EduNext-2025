<?php

namespace Modules\Core\Services;

use App\Events\AskLessonQuestionEvent;
use App\Http\Data\PageableData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\AiIntegration\Data\AiAnswerToQuestion;
use Modules\AiIntegration\Data\QuestionToAI;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Repositories\CourseRepository;
use Modules\Core\Repositories\LessonRepository;

readonly class LessonService
{
    public function __construct(
        private LessonRepository $lessonRepository
    ) {}

    public function findAll(LessonFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        return $this->lessonRepository->findAll($filterQuery, $pageableData);
    }

    public function store(Lesson $lessonToSave): Lesson
    {
        return $this->lessonRepository->store($lessonToSave);
    }

    public function updatePartial(Lesson $oldLesson, Lesson $newLesson): Lesson
    {
        return $this->lessonRepository->updatePartial($oldLesson, $newLesson);
    }

    public function restore(int $lessonsId): Lesson
    {
        return $this->lessonRepository->restore($lessonsId);
    }

    public function deleteSoft(Lesson $lesson): void
    {
        $this->lessonRepository->deleteSoft($lesson);
    }

    public function deleteHard(Lesson $lesson): void
    {
        $this->lessonRepository->deleteHard($lesson);
    }

    //тут уже асинхронно делать было лень :), так что немного колхоза
    public function askQuestion(Lesson $lesson, string $question): AiAnswerToQuestion
    {
        $questionToAi = new QuestionToAI($question);
        event(new AskLessonQuestionEvent($lesson, $questionToAi));
        return $questionToAi->getAnswer();
    }

}
