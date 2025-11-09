<?php

namespace Modules\Core\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\UserLesson;

class UserLessonFactory extends Factory
{
    protected $model = UserLesson::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'lesson_id' => Lesson::factory(),
            'course_id' => Course::factory(),
            'progress' => 0,
            'is_completed' => false,
        ];
    }
}
