<?php

namespace Modules\AiIntegration\Builder;

use Modules\AiIntegration\Requests\AiRequest;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\Task;

class LessonAiRequestBuilder
{

    public function generateLessonQuestion(Lesson $lesson, string $question): AiRequest
    {

        $course = $lesson->course;

        return new AiRequest(
            needToValidateContent: true,
            message: $question,
            prompt: "
Объясни тему из урока в контексте вопроса студента.НЕ предоставляй готовые решения или ответы на задания.
ДАННЫЕ УРОКА:
- Курс: {$course->title}  
- Тема: {$lesson->title}
- Контент: " . ($lesson->content ? substr(strip_tags($lesson->content), 0, 700) : "учебные материалы") . "

ВОПРОС: {$question}

СФОРМИРУЙ ОТВЕТ КАК:
1. Объяснение связанное с вопросом
2. Ключевые моменты из урока
3. Практические примеры

ВЫВЕДИ В ФОРМАТЕ JSON:
{
  \"answer\": \"Развернутый ответ\"
}

Только JSON, без других текстов."
        );
    }
}