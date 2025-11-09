<?php

namespace Modules\Core\Enums;

class TaskStatuses
{
    public const GENERATING = 'generating';
    public const PENDING_SOLUTION = 'pending_solution';
    public const UNDER_REVIEW = 'under_review';
    public const COMPLETED = 'completed';
    public const REJECTED = 'rejected';

    public static function all(): array
    {
        return [
            [
                'name' => 'Генерация',
                'slug' => self::GENERATING,
                'description' => 'Задача находится в процессе генерации искусственным интеллектом'
            ],
            [
                'name' => 'Ожидает решения',
                'slug' => self::PENDING_SOLUTION,
                'description' => 'Задача создана и ожидает решения от пользователя'
            ],
            [
                'name' => 'На проверке',
                'slug' => self::UNDER_REVIEW,
                'description' => 'Решение отправлено на проверку преподавателю или системе'
            ],
            [
                'name' => 'Выполнено',
                'slug' => self::COMPLETED,
                'description' => 'Задача успешно завершена и проверена'
            ],
            [
                'name' => 'Отклонено',
                'slug' => self::REJECTED,
                'description' => 'Задача требует доработки и повторной отправки на проверку'
            ],
        ];
    }
}