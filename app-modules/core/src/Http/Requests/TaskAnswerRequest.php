<?php

namespace Modules\Core\Http\Requests;

use Carbon\Carbon;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class TaskAnswerRequest extends Data
{
    public function __construct(
        public readonly string $answer,
    ) {}

    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'answer' => ['required', 'string', 'max:255',],
        ];
    }
}
