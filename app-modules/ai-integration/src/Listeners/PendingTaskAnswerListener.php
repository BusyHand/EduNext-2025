<?php

namespace Modules\AiIntegration\Listeners;

use App\Events\AcceptedTaskAnswerEvent;
use App\Events\PendingTaskAnswerEvent;
use App\Events\RejectedTaskAnswerEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\AiIntegration\Builder\TaskAiRequestBuilder;
use Modules\AiIntegration\Clients\OpenRouterClient;
use Modules\AiIntegration\Mappers\AiTaskMapper;

readonly class PendingTaskAnswerListener implements ShouldQueue
{

    public function __construct(
        private OpenRouterClient     $openRouterClient,
        private AiTaskMapper         $aiRequestMapper,
        private TaskAiRequestBuilder $taskPromptTemplate,
    ) {}

    public function handle(PendingTaskAnswerEvent $event): void
    {
        $aiRequest = $this->taskPromptTemplate->reviewAnswerAiRequest($event->task);
        $aiResponse = $this->openRouterClient->chat($aiRequest);
        $reviewedTask = $this->aiRequestMapper->toReviewedTask($aiResponse);
        if ($reviewedTask->isCorrect) {
            event(new AcceptedTaskAnswerEvent($event->task, $reviewedTask));
        } else {
            event(new RejectedTaskAnswerEvent($event->task, $reviewedTask));
        }
    }
}