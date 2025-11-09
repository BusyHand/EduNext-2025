<?php

namespace Modules\Core\Listeners;

use App\Events\RejectedTaskAnswerEvent;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Services\TaskService;

readonly class RejectedTaskAnswerListener
{

    public function __construct(
        private TaskService $taskService
    ) {}

    public function handle(RejectedTaskAnswerEvent $event): void
    {
        $task = $event->task;
        $task->feedback = $event->reviewedTask->feedback;
        $this->taskService->updateTaskStatus($event->task, TaskStatuses::REJECTED);
        $this->taskService->update($task);
    }
}