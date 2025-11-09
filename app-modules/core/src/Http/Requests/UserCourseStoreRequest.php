<?php

namespace Modules\Core\Http\Requests;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class UserCourseStoreRequest extends Data
{
    public function __construct(
        public readonly int $userId,
        public readonly int $courseId,
    )
    {
    }

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'userId' => ['required', 'integer', 'min:1',],
            'courseId' => ['required', 'integer', 'min:1',],
        ];
    }
}
