<?php

namespace App\Events;

use Modules\AiIntegration\Data\QuestionToAI;
use Modules\Core\Models\Lesson;

readonly class AskLessonQuestionEvent
{

    public function __construct(
        public Lesson $lesson,
        public QuestionToAI $question,
    ) {}
}