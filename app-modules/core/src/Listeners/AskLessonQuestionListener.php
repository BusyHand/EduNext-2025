<?php

namespace Modules\Core\Listeners;

use App\Events\AcceptedTaskAnswerEvent;
use App\Events\AskLessonQuestionEvent;
use Modules\AiIntegration\Builder\LessonAiRequestBuilder;
use Modules\AiIntegration\Clients\OpenRouterClient;
use Modules\AiIntegration\Mappers\AiLessonMapper;
use Modules\Core\Enums\TaskStatuses;
use Modules\Core\Services\TaskService;

readonly class AskLessonQuestionListener
{

    public function __construct(
        private OpenRouterClient       $openRouterClient,
        private AiLessonMapper         $aiLessonMapper,
        private LessonAiRequestBuilder $lessonAiRequestBuilder,
    ) {}

    public function handle(AskLessonQuestionEvent $event): void
    {
        $aiRequest = $this->lessonAiRequestBuilder->generateLessonQuestion($event->lesson, $event->question->getQuestion());
        $aiResponse = $this->openRouterClient->chat($aiRequest, 'content');
        $answer = $this->aiLessonMapper->toAnswer($aiResponse);
        $event->question->setAnswer($answer);
    }
}