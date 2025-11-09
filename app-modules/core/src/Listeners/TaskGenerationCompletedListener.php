<?php

namespace Modules\Core\Listeners;

use App\Events\TaskGenerationCompletedEvent;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Services\TaskService;

readonly class TaskGenerationCompletedListener
{

    public function __construct(
        private TaskService $taskService
    ) {}

    public function handle(TaskGenerationCompletedEvent $event): void
    {
        $task = $event->task;
        $task->content = $event->generatedTask->content;
        $task->solution = $event->generatedTask->solution;
        $this->taskService->updateTaskStatus($task, TaskStatuses::PENDING_SOLUTION);
        $this->taskService->update($task);

    }
}