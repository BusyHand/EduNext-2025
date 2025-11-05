<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserLessonDto extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly int     $user_id,
        public readonly int     $lesson_id,
        public readonly int     $course_id,
        public readonly int     $progress,
        public readonly bool    $is_completed,
        public readonly ?Carbon $completed_at,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'id'           => ['integer', 'min:1'],
            'user_id'      => ['integer', 'min:1', 'required'],
            'lesson_id'    => ['integer', 'min:1', 'required'],
            'course_id'    => ['integer', 'min:1', 'required'],
            'progress'     => ['integer', 'min:0', 'max:100'],
            'is_completed' => ['boolean'],
            'completed_at' => ['nullable', 'date'],
        ];
    }
}
