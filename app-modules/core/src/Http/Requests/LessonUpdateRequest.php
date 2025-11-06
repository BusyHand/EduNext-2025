<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class LessonUpdateRequest extends Data
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $content = null,
        public readonly ?bool   $isPublished = null,
    )
    {
    }

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'title' => ['sometimes', 'required', 'filled', 'string', 'max:255'],
            'content' => ['sometimes', 'required', 'string', 'max:2000',],
            'isPublished' => ['boolean'],
        ];
    }
}
