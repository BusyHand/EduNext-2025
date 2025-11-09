<?php

namespace Modules\Core\Http\Filters;

use Czim\Filter\Filter;
use Modules\Core\Http\Filters\FilterStrategies\CreatedAfterFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\CreatedBeforeFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\OwnerFilterStrategy;
use Modules\Core\Http\Filters\FilterStrategies\TitleFilterStrategy;

class CourseFilter extends Filter
{
    protected function strategies(): array
    {
        return [
            'title' => TitleFilterStrategy::class,
            'owner' => OwnerFilterStrategy::class,
            'createdAfter' => CreatedAfterFilterStrategy::class,
            'createdBefore' => CreatedBeforeFilterStrategy::class,
        ];
    }
}
