<?php

namespace Modules\Core\Http\Filters\FilterStrategies;

use Czim\Filter\Contracts\FilterInterface;
use Czim\Filter\Contracts\ParameterFilterInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;

class CreatedAfterFilterStrategy implements ParameterFilterInterface
{

    public function apply(
        string                        $name,
        mixed                         $value,
        Model|Builder|EloquentBuilder $query,
        FilterInterface               $filter,
    ): Model|Builder|EloquentBuilder
    {
        return $query->whereDate('created_at', '>=', $value);
    }
}
