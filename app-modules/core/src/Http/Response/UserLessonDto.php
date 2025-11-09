<?php

namespace Modules\Core\Http\Response;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserLessonDto extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $userId,
        public readonly int     $lessonId,
        public readonly int     $courseId,
        public readonly ?int     $progress,
        public readonly ?bool    $isCompleted,
        public readonly ?Carbon $completedAt,
    ) {}
}
