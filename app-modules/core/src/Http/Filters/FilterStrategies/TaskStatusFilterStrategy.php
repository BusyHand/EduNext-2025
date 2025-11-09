<?php

namespace Modules\Core\Http\Filters\FilterStrategies;

use Czim\Filter\Contracts\FilterInterface;
use Czim\Filter\Contracts\ParameterFilterInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Modules\Core\Services\TaskStatusService;

class TaskStatusFilterStrategy implements ParameterFilterInterface
{

    public function apply(
        string                        $name,
        mixed                         $value,
        Model|Builder|EloquentBuilder $query,
        FilterInterface               $filter,
    ): Model|Builder|EloquentBuilder
    {
        $taskStatusService = app(TaskStatusService::class);
        $taskStatusName = $value;
        $taskStatus = $taskStatusService->findBySlug($taskStatusName);
        return $query->where('task_status_id', $taskStatus->id);
    }
}
