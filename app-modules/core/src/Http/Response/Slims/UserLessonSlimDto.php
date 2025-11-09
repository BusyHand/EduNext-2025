<?php

namespace Modules\Core\Http\Response\Slims;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserLessonSlimDto extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $title,
        public readonly ?string $content,
        public readonly ?int    $courseId,
        public readonly ?Carbon $createdAt,
    ) {}
}
