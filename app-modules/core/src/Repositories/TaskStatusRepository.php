<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Task;
use Modules\Core\Models\TaskStatus;

class TaskStatusRepository
{
    public function findBySlug(string $slug): TaskStatus
    {
        return TaskStatus::where('slug', $slug)
            ->firstOrFail();
    }
}
