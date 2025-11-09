<?php

namespace Modules\AiIntegration\Requests;

readonly class AiRequest
{
    public function __construct(
        public bool    $needToValidateContent = false,
        public ?string $message,
        public ?string $prompt = null,
    ) {}
}