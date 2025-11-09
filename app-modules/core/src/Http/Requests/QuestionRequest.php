<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class QuestionRequest extends Data
{
    public function __construct(
        public readonly string $question,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'question' => ['required', 'string', 'max:255',],
        ];
    }
}
