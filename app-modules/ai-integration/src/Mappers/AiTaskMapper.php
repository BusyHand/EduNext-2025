<?php

namespace Modules\AiIntegration\Mappers;

use InvalidArgumentException;
use Modules\AiIntegration\Data\GeneratedTask;
use Modules\AiIntegration\Data\ReviewedTask;
use Modules\AiIntegration\Responses\AiResponse;

readonly class AiTaskMapper
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

    public function toGeneratedTask(AiResponse $aiResponse): GeneratedTask
    {
        $jsonContent = $this->toJson($aiResponse);
        return new GeneratedTask(
            content: $jsonContent['content'],
            solution: $jsonContent['solution'],
        );
    }

    public function toReviewedTask(AiResponse $aiResponse): ReviewedTask
    {
        $jsonContent = $this->toJson($aiResponse);
        return new ReviewedTask(
            isCorrect: $jsonContent['is_correct'],
            feedback: $jsonContent['feedback'],
        );

    }


}