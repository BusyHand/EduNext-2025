<?php

namespace App\Events;

use Modules\AiIntegration\Data\GeneratedTask;
use Modules\Core\Models\Task;

readonly class TaskGenerationCompletedEvent
{
    public function __construct(
        public Task          $task,
        public GeneratedTask $generatedTask,
    ) {}
}