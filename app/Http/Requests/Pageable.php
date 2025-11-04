<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Builder;

trait Pageable
{
    public function applyPagination(Builder $query, PageableData $pageableData): Builder
    {
        foreach ($pageableData->getSorts() as $sort) {
            $query->orderBy($sort['field'], $sort['direction']);
        }
        return $query->forPage($pageableData->getPage(), $pageableData->getSize());
    }
}
