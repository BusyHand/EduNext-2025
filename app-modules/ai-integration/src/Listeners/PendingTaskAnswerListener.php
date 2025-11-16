<?php

namespace Modules\AiIntegration\Listeners;

use App\Events\AcceptedTaskAnswerEvent;
use App\Events\PendingTaskAnswerEvent;
use App\Events\RejectedTaskAnswerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AiIntegration\Builder\TaskAiRequestBuilder;
use Modules\AiIntegration\Clients\OpenRouterClient;
use Modules\AiIntegration\Mappers\AiTaskMapper;

class PendingTaskAnswerListener implements ShouldQueue
{

    public int $tries = 5;

    public array $backoff = [5, 10, 30, 60, 120];

    public function __construct(
        private readonly OpenRouterClient     $openRouterClient,
        private readonly AiTaskMapper         $aiRequestMapper,
        private readonly TaskAiRequestBuilder $taskPromptTemplate,
    ) {}

    public function handle(PendingTaskAnswerEvent $event): void
    {
        $aiRequest = $this->taskPromptTemplate->reviewAnswerAiRequest($event->task);
        $aiResponse = $this->openRouterClient->chat($aiRequest, 'reasoning');
        $reviewedTask = $this->aiRequestMapper->toReviewedTask($aiResponse);
        if ($reviewedTask->isCorrect) {
            event(new AcceptedTaskAnswerEvent($event->task, $reviewedTask));
        } else {
            event(new RejectedTaskAnswerEvent($event->task, $reviewedTask));
        }
    }
}