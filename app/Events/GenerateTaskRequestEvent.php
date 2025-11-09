<?php

namespace App\Events;

use Modules\Core\Models\Task;

readonly class GenerateTaskRequestEvent
{
    public function __construct(
        public Task $task,
    ) {}

}