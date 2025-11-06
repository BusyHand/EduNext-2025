<?php

namespace Modules\Core\Http\Filters;

use Czim\Filter\Filter;
use Modules\Core\Http\Data\CourseFilterData;
use Modules\Core\Http\Filters\FilterStrategies\CourseCreatedAfterFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CourseCreatedBeforeFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CourseOwnerFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CourseTitleFilterStrategy;

class CourseFilter extends Filter
{
    protected string $filterDataClass = CourseFilterData::class;

    protected function strategies(): array
    {
        return [
            'title' => CourseTitleFilterStrategy::class,
            'owner' => CourseOwnerFilterStrategy::class,
            'createdAfter' => CourseCreatedAfterFilterStrategy::class,
            'createdBefore' => CourseCreatedBeforeFilterStrategy::class,
        ];
    }
}
