<?php

namespace Modules\Core\Http\Filters;

use Czim\Filter\Filter;
use Modules\Core\Http\Filters\Data\CourseFilterData;
use Modules\Core\Http\Filters\FilterStrategies\CourseTitleFilterStrategy;

class CourseFilter extends Filter
{
    protected string $filterDataClass = CourseFilterData::class;

    protected function strategies(): array
    {
        return [
            'title' => CourseTitleFilterStrategy::class,
        ];
    }
}
