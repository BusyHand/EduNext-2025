<?php

namespace Modules\AiIntegration\Data;

use Spatie\LaravelData\Data;

class AiAnswerToQuestion extends Data
{

    public function __construct(
        public readonly string $answer,
    ) {}
}