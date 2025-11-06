<?php

namespace Modules\Core\Http\Response;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class LessonDto extends Data
{
    public function __construct(
        public readonly ?int    $id,
        public readonly ?string $title,
        public readonly ?string $content,
        public readonly ?bool   $isPublished,
        public readonly ?Carbon $publishedAt,
        public readonly ?int    $courseId,
        public readonly ?int    $createdBy,
        public readonly ?int    $updatedBy,
    )
    {
    }
}
