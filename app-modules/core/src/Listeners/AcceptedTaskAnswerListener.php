<?php

namespace Modules\Core\Listeners;

use App\Events\AcceptedTaskAnswerEvent;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Services\TaskService;

readonly class AcceptedTaskAnswerListener
{

    public function __construct(
        private TaskService $taskService
    ) {}

    public function handle(AcceptedTaskAnswerEvent $event): void
    {
        $task = $event->task;
        $task->feedback = $event->reviewedTask->feedback;
        $this->taskService->updateTaskStatus($event->task, TaskStatuses::COMPLETED);
        $this->taskService->update($task);
    }
}