<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Dtos\PaginateDto;
use Modules\Core\Http\Filters\Requests\TaskFilterRequest;
use Modules\Core\Http\Mappers\TaskMapper;
use Modules\Core\Http\Requests\TaskAnswerRequest;
use Modules\Core\Http\Response\TaskDto;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\Task;
use Modules\Core\Services\TaskService;


readonly class TaskController
{
    public function __construct(
        private TaskService $taskService,
        private TaskMapper  $taskMapper,
    ) {}

    public function findAll(TaskFilterRequest $filterRequest): PaginateDto
    {
        $filterQuery = $this->taskMapper->toFilter($filterRequest);
        $paginateLessons = $this->taskService->findAll($filterQuery, $filterRequest->toPageableData());
        return $this->taskMapper->toPaginateDtos($paginateLessons);
    }

    public function generateTask(Lesson $lesson): TaskDto
    {
        $task = $this->taskService->generateTask($lesson);
        return $this->taskMapper->toDto($task);
    }

    public function answerTask(Task $task, TaskAnswerRequest $answer): TaskDto
    {
        $task = $this->taskService->reviewAnswer($task, $answer->answer);
        return $this->taskMapper->toDto($task);
    }
}
