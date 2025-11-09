<?php

namespace Modules\AiIntegration\Data;

class QuestionToAI
{

    public function __construct(
        private string $question,
        private ?AiAnswerToQuestion $answer = null,
    ) {}

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): AiAnswerToQuestion
    {
        return $this->answer;
    }

    public function setAnswer(AiAnswerToQuestion $answer): void
    {
        $this->answer = $answer;
    }


}