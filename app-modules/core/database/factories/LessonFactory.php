<?php

namespace Modules\Core\Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'content' => $this->faker->optional()->paragraph(),
            'is_published' => true,
            'course_id' => Course::factory(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
