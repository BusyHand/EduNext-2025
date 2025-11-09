<?php

namespace Modules\AiIntegration\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class IntegrationWithAiNotAvailableNow extends RuntimeException
{
    protected array $errors;

    public function __construct(string $message = 'Integration with ai not available now', array $errors = [])
    {
        parent::__construct($message);
        $this->errors = $errors;
    }

    public function render(Request $request): JsonResponse
    {
        $response = [
            'message' => $this->getMessage(),
        ];

        if (!empty($this->errors)) {
            $response['errors'] = $this->errors;
        }

        return response()->json($response, 422);
    }
}