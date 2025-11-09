<?php

namespace App\Traits;

use App\Http\Data\PageableData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

trait Pageable
{
    public function paginate(Builder $query, PageableData $pageableData): LengthAwarePaginator
    {
        foreach ($pageableData->getSorts() as $sort) {
            $query->orderBy($sort['field'], $sort['direction']);
        }
        return $query->paginate(
            $pageableData->getSize(),
            ['*'],
            'page',
            $pageableData->getPage()
        );
    }
}
