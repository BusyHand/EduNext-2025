<?php

namespace Modules\AiIntegration\Listeners;

use App\Events\GenerateTaskRequestEvent;
use App\Events\TaskGenerationCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AiIntegration\Builder\TaskAiRequestBuilder;
use Modules\AiIntegration\Clients\OpenRouterClient;
use Modules\AiIntegration\Mappers\AiTaskMapper;

readonly class GenerateTaskRequestListener implements ShouldQueue
{

    public function __construct(
        private OpenRouterClient     $openRouterClient,
        private AiTaskMapper         $aiResponseMapper,
        private TaskAiRequestBuilder $taskPromptBuilder,
    ) {}

    public function handle(GenerateTaskRequestEvent $event): void
    {
        $aiRequest = $this->taskPromptBuilder->generateTaskAiRequest($event->task);
        $aiResponse = $this->openRouterClient->chat($aiRequest);
        $generatedTask = $this->aiResponseMapper->toGeneratedTask($aiResponse);
        event(new TaskGenerationCompletedEvent($event->task, $generatedTask));
    }
}