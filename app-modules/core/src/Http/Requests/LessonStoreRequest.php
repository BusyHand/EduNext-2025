<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class LessonStoreRequest extends Data
{
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly bool   $isPublished,
        public readonly int    $courseId,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'title' => ['required', 'string', 'min:15', 'max:255',],
            'content' => ['required', 'string', 'max:2000',],
            'courseId' => ['required', 'integer', 'min:1',],
            'isPublished' => ['boolean'],
        ];
    }
}
