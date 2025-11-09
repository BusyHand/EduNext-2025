<?php

namespace Modules\Core\Http\Filters;

use Czim\Filter\Filter;
use Modules\Core\Http\Filters\FilterStrategies\CourseFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CreatedAfterFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CreatedBeforeFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\IsCompletedLessonFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\LessonFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\TitleFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\UserFilterStrategy;

class UserLessonFilter extends Filter
{
    protected function strategies(): array
    {
        return [
            'user' => UserFilterStrategy::class,
            'course' => CourseFilterStrategy::class,
            'lesson' => LessonFilterStrategy::class,
            'isCompleted' => IsCompletedLessonFilterStrategy::class,
        ];
    }
}
