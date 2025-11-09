<?php

namespace App\Events;

use Modules\AiIntegration\Data\ReviewedTask;
use Modules\Core\Models\Task;

readonly class AcceptedTaskAnswerEvent
{

    public function __construct(
        public Task         $task,
        public ReviewedTask $reviewedTask,
    ) {}
}