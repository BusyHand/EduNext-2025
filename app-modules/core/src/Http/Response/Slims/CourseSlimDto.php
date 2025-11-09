<?php

namespace Modules\Core\Http\Response\Slims;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CourseSlimDto extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $title,
        public readonly ?string $description,
        public readonly ?int    $ownerId,
        public readonly ?Carbon   $createdAt,
    ) {}
}
