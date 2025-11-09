<?php

namespace Modules\AiIntegration\Mappers;

use InvalidArgumentException;
use Modules\AiIntegration\Data\AiAnswerToQuestion;
use Modules\AiIntegration\Data\GeneratedTask;
use Modules\AiIntegration\Data\QuestionToAI;
use Modules\AiIntegration\Data\ReviewedTask;
use Modules\AiIntegration\Responses\AiResponse;

readonly class AiLessonMapper
{

    private function toJson(AiResponse $aiResponse): array
    {
        $content = $aiResponse->content;

        preg_match('/\{[^{}]*(?:\{[^{}]*\}[^{}]*)*\}/', $content, $matches);

        if (empty($matches)) {
            preg_match('/\[[^\[\]]*(?:\[[^\[\]]*\][^\[\]]*)*\]/', $content, $matches);
        }

        if (empty($matches)) {
            throw new InvalidArgumentException('No valid JSON found in AI response');
        }

        $jsonString = $matches[0];

        if (!json_validate($jsonString)) {
            throw new InvalidArgumentException('Invalid JSON in AI response');
        }

        $decoded = json_decode($jsonString, true);

        if ($decoded === null && $jsonString !== 'null') {
            throw new InvalidArgumentException('Invalid JSON in AI response');
        }
        return $decoded;
    }

    public function toAnswer(AiResponse $aiResponse): AiAnswerToQuestion
    {
        $json = $this->toJson($aiResponse);
        return new AiAnswerToQuestion(
            answer: $json['answer'],
        );
    }

}