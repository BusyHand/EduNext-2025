<?php

namespace Modules\Core\Services;

use App\Events\GenerateTaskRequestEvent;
use App\Events\PendingTaskAnswerEvent;
use App\Http\Data\PageableData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Http\Filters\TaskFilter;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\Task;
use Modules\Core\Repositories\TaskRepository;

readonly class TaskService
{
    public function __construct(
        private TaskRepository    $taskRepository,
        private TaskStatusService $taskStatusService,
    ) {}

    public function findAll(TaskFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        return $this->taskRepository->findAll($filterQuery, $pageableData);
    }

    public function update(Task $task): Task
    {
        return $this->taskRepository->update($task);
    }

    public function updateTaskStatus(Task $task, string $status): Task
    {
        $task->task_status_id = $this->taskStatusService->findBySlug($status)->id;
        $updateTask = $this->taskRepository->update($task);
        return $updateTask;
    }

    public function reviewAnswer(Task $task, string $answer): Task
    {
        $task->last_answer = $answer;
        $task = $this->updateTaskStatus($task, TaskStatuses::UNDER_REVIEW);
        $updateTask = $this->taskRepository->update($task);
        event(new PendingTaskAnswerEvent($updateTask));
        return $updateTask;
    }

    public function generateTask(Lesson $lesson): Task
    {
        $userId = Auth::id();
        $savedTask = $this->taskRepository->store(new Task([
            'user_id' => $userId,
            'lesson_id' => $lesson->id,
            'course_id' => $lesson->course_id,
            'task_status_id' => $this->taskStatusService->findBySlug(TaskStatuses::GENERATING)->id
        ]));
        event(new GenerateTaskRequestEvent($savedTask));
        return $savedTask;
    }
}
