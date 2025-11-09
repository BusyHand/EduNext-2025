<?php

namespace Modules\AiIntegration\Builder;

use Modules\AiIntegration\Requests\AiRequest;
use Modules\Core\Models\Task;

class TaskAiRequestBuilder
{

    public function generateTaskAiRequest(Task $task): AiRequest
    {
        $lesson = $task->lesson;
        $course = $task->course;

        return new AiRequest(
            message: $lesson->content,
            prompt: "Создай учебное задание на русском языке в формате JSON со следующей структурой:\n\n" .
            "Отправь строго ответ по данному json и ничего лишнего \n" .
            "{\n" .
            "  \"content\": \"Подробное описание задания\",\n" .
            "  \"solution\": \"Пример решения\",\n" .
            "}\n\n" .
            "Требования к заданию:\n" .
            "- Тема урока: {$lesson->title}\n" .
            "- Курс: {$course->title}\n" .
            "- Контекст урока: " . ($lesson->content ? substr($lesson->content, 0, 500) . "..." : "информация о уроке") . "\n\n" .
            "Сделай задание:\n" .
            "- Практичным и полезным\n" .
            "- Соответствующим теме урока\n" .
            "- С четкими инструкциями\n" .
            "- С подробным решением\n" .
            "- С полезными подсказками для самостоятельной работы"
        );
    }

    public function reviewAnswerAiRequest(Task $task): AiRequest
    {
        $lesson = $task->lesson;

        return new AiRequest(
            needToValidateContent: true,
            message: $task->last_answer,
            prompt: "Оцени ответ студента без раскрытия правильных ответов.
ЗАПРЕЩЕНО В ФИДБЕКЕ:
- Правильные ответы
- Конкретные верные варианты  
- Что должно быть написано
- Слово 'правильный' в контексте ответа
- Любые подсказки о точном решении

КОНТЕКСТ:
Задание: {$task->content}
Тема: {$lesson->title}
Правильное решение: {$task->solution}
Ответ студента: {$task->last_answer}

ФОКУС ФИДБЕКА:
- Общие рекомендации по улучшению
- Указание на пробелы в понимании
- Направления для дальнейшего изучения
- Советы по методологии

ПРИМЕРЫ ДОПУСТИМОГО ФИДБЕКА:
✓ 'Ответ требует более точной формулировки'
✓ 'Рекомендую обратить внимание на терминологию'
✓ 'Необходимо более развернутое объяснение'

ПРИМЕРЫ НЕДОПУСТИМОГО ФИДБЕКА:
✗ 'Правильный ответ: \"вова\"'
✗ 'Должно быть написано именно это слово'
✗ 'Верный вариант: ...'

JSON СТРУКТУРА:
{
  \"is_correct\": true/false,
  \"feedback\": \"Общая обратная связь без конкретных правильных ответов\"
}

Только JSON, без других текстов."
    );
}
}