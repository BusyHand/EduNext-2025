<?php

namespace Modules\Core\Http\Response;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class TaskDto extends Data
{
    public function __construct(
        public readonly ?int    $id,
        public readonly ?string $userId,
        public readonly ?string $lessonId,
        public readonly ?string $courseId,
        public readonly ?string $status,
        public readonly ?Carbon    $createdAt,
        public readonly ?string $content,
        public readonly ?string $feedback,
        public readonly ?string $lastAnswer,
    ) {}
}
