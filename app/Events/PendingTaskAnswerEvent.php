<?php

namespace App\Events;

use Modules\Core\Models\Task;

readonly class PendingTaskAnswerEvent
{
    public function __construct(
        public Task $task,
    ) {}

}