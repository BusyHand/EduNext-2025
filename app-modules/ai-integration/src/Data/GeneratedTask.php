<?php

namespace Modules\AiIntegration\Data;

readonly class GeneratedTask
{
    public function __construct(
        public string $content,
        public string $solution,
    ) {}
}