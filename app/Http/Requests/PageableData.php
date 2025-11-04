<?php

namespace App\Http\Requests;

readonly class PageableData
{
    public function __construct(
        private ?array $sorts = [],
        private ?int   $page = 1,
        private ?int   $size = 15
    ){}

    public function getSorts(): array
    {
        return $this->sorts ?? [];
    }

    public function getPage(): int
    {
        return $this->page ?? 1;
    }

    public function getSize(): int
    {
        return $this->size ?? 15;
    }
}
