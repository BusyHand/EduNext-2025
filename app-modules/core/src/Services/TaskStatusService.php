<?php

namespace Modules\Core\Services;

use Modules\Core\Models\TaskStatus;
use Modules\Core\Repositories\TaskStatusRepository;

readonly class TaskStatusService
{
    public function __construct(
        private TaskStatusRepository $taskStatusRepository,
    ) {}

    public function findBySlug(string $slug): TaskStatus
    {
        return $this->taskStatusRepository->findBySlug($slug);
    }
}
