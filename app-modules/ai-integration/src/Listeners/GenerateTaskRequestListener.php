<?php

namespace Modules\AiIntegration\Listeners;

use App\Events\GenerateTaskRequestEvent;
use App\Events\TaskGenerationCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AiIntegration\Builder\TaskAiRequestBuilder;
use Modules\AiIntegration\Clients\OpenRouterClient;
use Modules\AiIntegration\Mappers\AiTaskMapper;

class GenerateTaskRequestListener implements ShouldQueue
{

    public int $tries = 5;

    public array $backoff = [5, 10, 30, 60, 120];

    public function __construct(
        private readonly OpenRouterClient     $openRouterClient,
        private readonly AiTaskMapper         $aiResponseMapper,
        private readonly TaskAiRequestBuilder $taskPromptBuilder,
    ) {}

    public function handle(GenerateTaskRequestEvent $event): void
    {
        $aiRequest = $this->taskPromptBuilder->generateTaskAiRequest($event->task);
        $aiResponse = $this->openRouterClient->chat($aiRequest);
        $generatedTask = $this->aiResponseMapper->toGeneratedTask($aiResponse);
        event(new TaskGenerationCompletedEvent($event->task, $generatedTask));
    }
}