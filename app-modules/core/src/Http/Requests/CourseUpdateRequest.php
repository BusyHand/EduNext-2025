<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CourseUpdateRequest extends Data
{
    public function __construct(
        public readonly ?string $title = null,
        public readonly ?string $description = null,
        public readonly ?bool   $isPublished = null,
    )
    {
    }

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'title' => ['required_if:title,!=,null', 'filled', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'isPublished' => ['boolean'],
        ];
    }
}
