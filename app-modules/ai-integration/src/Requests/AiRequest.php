<?php

namespace Modules\AiIntegration\Requests;

readonly class AiRequest
{
    public function __construct(
        public bool    $needToValidateContent,
        public ?string $message,
        public ?string $prompt = null,
    ) {}
}