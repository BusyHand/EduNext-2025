<?php

namespace App\Http\Dtos;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\LaravelData\Data;

class PaginateDto extends Data
{
    public function __construct(
        public readonly array $data,
        public readonly array $meta,
    )
    {
    }

    public static function toPaginateDto(LengthAwarePaginator $paginator, callable $itemMapper): self
    {
        $data = $paginator->getCollection()->map($itemMapper)->toArray();

        return new self(
            data: $data,
            meta: [
                'currentPage' => $paginator->currentPage(),
                'perPage' => $paginator->perPage(),
                'total' => $paginator->total(),
                'lastPage' => $paginator->lastPage(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        );
    }
}
