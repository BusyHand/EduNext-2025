<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Http\Filters\TaskFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\Task;

class TaskRepository
{
    use Pageable;

    public function findAll(TaskFilter $filterQuery, PageableData $pageableData)
    {
        $query = $filterQuery->apply(Task::with(['status']));
        return $this->paginate($query, $pageableData);
    }

    public function store(Task $task): Task
    {
        $task->save();
        return $task;
    }

    public function update(Task $task): Task
    {
        $task->update();
        return $task;
    }

}
