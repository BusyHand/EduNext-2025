<?php

namespace Modules\AiIntegration\Data;

readonly class ReviewedTask
{
    public function __construct(
        public bool   $isCorrect,
        public string $feedback,
    ) {}
}