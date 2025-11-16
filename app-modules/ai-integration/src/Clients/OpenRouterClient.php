<?php

namespace Modules\AiIntegration\Clients;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Modules\AiIntegration\Enums\AiModels;
use Modules\AiIntegration\Exceptions\IntegrationWithAiNotAvailableNow;
use Modules\AiIntegration\Requests\AiRequest;
use Modules\AiIntegration\Responses\AiResponse;
use Modules\AiIntegration\Validators\AiRequestValidator;

readonly class OpenRouterClient
{
    private string $apiKey;
    private string $baseUrl;

    public function __construct(
        private AiModels           $aiModels,
        private AiRequestValidator $requestValidator,
    )
    {
        $this->apiKey = config('openrouter.api_key');
        $this->baseUrl = config('openrouter.api_endpoint');
    }

    public function chat(AiRequest $aiRequest, string $whatNeeded): AiResponse
    {
        if ($aiRequest->needToValidateContent) {
            $this->requestValidator->validateRequest($aiRequest->message);
        }
        $response = $this->sendToChat($aiRequest->prompt);
        return new AiResponse($response->json('choices.0.message.' . $whatNeeded));
    }

    public function sendToChat(string $prompt): Response
    {
        $aiModelNames = $this->aiModels->allAiModels();
        foreach ($aiModelNames as $aiModelName) {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . trim($this->apiKey),
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
                ->timeout(300)
                ->connectTimeout(60) // 60 секунд на соединение
                ->retry(3, 100) // 3 попытки с задержкой 100мс
                ->withBody(json_encode([
                    'model' => $aiModelName,
                    'messages' => [[
                        'role' => 'user',
                        'content' => $prompt,
                    ]],
                ], JSON_UNESCAPED_UNICODE))
                ->post("{$this->baseUrl}/chat/completions");
            if ($response->successful()) {
                return $response;
            }
        }
        throw new IntegrationWithAiNotAvailableNow();
    }
}
