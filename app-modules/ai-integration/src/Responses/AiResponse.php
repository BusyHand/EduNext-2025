<?php

namespace Modules\AiIntegration\Responses;

readonly class AiResponse
{
    public function __construct(public string $content) {}
}