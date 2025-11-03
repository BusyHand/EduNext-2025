<?php

namespace Modules\Core\Http\Data;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class LessonData extends Data
{
    public function __construct(
        public readonly int     $id,
        public readonly string  $title,
        public readonly ?string $content,
        public readonly bool    $is_published,
        public readonly ?Carbon $published_at,
        public readonly int     $course_id,
        public readonly ?int    $created_by,
        public readonly ?int    $updated_by,
        public readonly ?int    $deleted_by,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'id'           => ['integer', 'min:1'],
            'title'        => ['string', 'max:255', 'required'],
            'content'      => ['nullable', 'string'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'course_id'    => ['integer', 'min:1', 'required'],
            'created_by'   => ['nullable', 'integer', 'min:1'],
            'updated_by'   => ['nullable', 'integer', 'min:1'],
            'deleted_by'   => ['nullable', 'integer', 'min:1'],
        ];
    }
}
