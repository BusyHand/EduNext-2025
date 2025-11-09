<?php

namespace Modules\AiIntegration\Enums;

class AiModels
{
    public const DEEPSEEK_V3_1 = 'deepseek/deepseek-chat-v3.1:free';
    public const DEEPSEEK_R1T2 = 'tngtech/deepseek-r1t2-chimera:free';
    public const DEEPSEEK_R1T = 'tngtech/deepseek-r1t-chimera:free';
    public const DEEPSEEK_R1_0528 = 'deepseek/deepseek-r1-0528:free';
    public const DEEPSEEK_CHAT_V3_0324 = 'deepseek/deepseek-chat-v3-0324:free';
    public const DEEPSEEK_R1 = 'deepseek/deepseek-r1:free';
    public const OPENAI_GPT_OSS_20B = 'openai/gpt-oss-20b:free';

    private array $aiModels;

    public function __construct()
    {
        $this->aiModels = [
            AiModels::DEEPSEEK_R1T2,
            AiModels::DEEPSEEK_R1T,
            AiModels::DEEPSEEK_CHAT_V3_0324,
            AiModels::DEEPSEEK_R1_0528,
            AiModels::DEEPSEEK_V3_1,
            AiModels::DEEPSEEK_R1,
            AiModels::OPENAI_GPT_OSS_20B,
        ];
    }

    public function allAiModels(): array
    {
        return $this->aiModels;
    }
}