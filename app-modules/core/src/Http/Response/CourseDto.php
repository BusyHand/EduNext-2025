<?php

namespace Modules\Core\Http\Response;

use Carbon\Carbon;
use Spatie\LaravelData\Data;

class CourseDto extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $title,
        public readonly ?string $description,
        public readonly bool    $isPublished,
        public readonly ?Carbon $publishedAt,
        public readonly ?int    $ownerId,
        public readonly ?int    $createdBy,
        public readonly ?int    $updatedBy,
    ) {}
}
