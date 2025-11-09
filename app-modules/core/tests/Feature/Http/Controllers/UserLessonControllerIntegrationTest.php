<?php

namespace Modules\Core\Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Modules\Core\Models\UserLesson;
use Tests\TestCase;

class UserLessonControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Создаем тестового пользователя
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_returns_all_user_lessons_with_pagination()
    {
        UserLesson::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/users/lessons?page=2&size=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'userId', 'lessonId', 'courseId', 'progress', 'isCompleted', 'completedAt']
                ],
                'meta' => [
                    'currentPage',
                    'perPage',
                    'total',
                    'lastPage',
                    'from',
                    'to'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'currentPage' => 2,
                    'perPage' => 10,
                    'total' => 25,
                    'lastPage' => 3,
                ],
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function it_filters_user_lessons_by_user_id()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        UserLesson::factory()->create(['user_id' => $user1->id]);
        UserLesson::factory()->create(['user_id' => $user1->id]);
        UserLesson::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson("/api/v1/users/lessons?user={$user1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_filters_user_lessons_by_course_id()
    {
        $course1 = Course::factory()->create();
        $course2 = Course::factory()->create();

        UserLesson::factory()->create(['course_id' => $course1->id]);
        UserLesson::factory()->create(['course_id' => $course1->id]);
        UserLesson::factory()->create(['course_id' => $course2->id]);

        $response = $this->getJson("/api/v1/users/lessons?course={$course1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_filters_user_lessons_by_lesson_id()
    {
        $lesson1 = Lesson::factory()->create();
        $lesson2 = Lesson::factory()->create();

        UserLesson::factory()->create(['lesson_id' => $lesson1->id]);
        UserLesson::factory()->create(['lesson_id' => $lesson2->id]);
        UserLesson::factory()->create(['lesson_id' => $lesson2->id]);

        $response = $this->getJson("/api/v1/users/lessons?lesson={$lesson2->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_combines_multiple_filters()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        UserLesson::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id,
            'progress' => 50
        ]);
        UserLesson::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'lesson_id' => Lesson::factory()->create()->id
        ]);
        UserLesson::factory()->create([
            'user_id' => User::factory()->create()->id,
            'course_id' => $course->id,
            'lesson_id' => $lesson->id
        ]);

        $response = $this->getJson("/api/v1/users/lessons?user={$user->id}&course={$course->id}&lesson={$lesson->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_sorts_user_lessons_by_progress_asc()
    {
        UserLesson::factory()->create(['progress' => 100]);
        UserLesson::factory()->create(['progress' => 0]);
        UserLesson::factory()->create(['progress' => 50]);

        $response = $this->getJson('/api/v1/users/lessons?sort=progress,asc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals(0, $data[0]['progress']);
        $this->assertEquals(50, $data[1]['progress']);
        $this->assertEquals(100, $data[2]['progress']);
    }

    /** @test */
    public function it_sorts_user_lessons_by_is_completed_desc()
    {
        UserLesson::factory()->create(['is_completed' => true]);
        UserLesson::factory()->create(['is_completed' => false]);
        UserLesson::factory()->create(['is_completed' => true]);

        $response = $this->getJson('/api/v1/users/lessons?sort=is_completed,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertTrue($data[0]['isCompleted']);
        $this->assertTrue($data[1]['isCompleted']);
        $this->assertFalse($data[2]['isCompleted']);
    }

    /** @test */
    public function it_sorts_user_lessons_by_created_at_desc()
    {
        $userLesson1 = UserLesson::factory()->create(['created_at' => now()->subDays(2)]);
        $userLesson2 = UserLesson::factory()->create(['created_at' => now()->subDays(1)]);
        $userLesson3 = UserLesson::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/v1/users/lessons?sort=created_at,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($userLesson3->id, $data[0]['id']);
        $this->assertEquals($userLesson2->id, $data[1]['id']);
        $this->assertEquals($userLesson1->id, $data[2]['id']);
    }

    /** @test */
    public function it_stores_user_lesson_successfully()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);

        $response = $this->postJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'userId',
                'lessonId',
                'courseId',
                'progress',
                'isCompleted',
                'completedAt'
            ])
            ->assertJson([
                'userId' => $user->id,
                'lessonId' => $lesson->id,
                'courseId' => $course->id,
                'progress' => 0,
                'isCompleted' => false,
            ]);
        $this->assertNull($response->json('completedAt'));

        $this->assertDatabaseHas('user_lessons', [
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'course_id' => $course->id,
            'progress' => 0,
            'is_completed' => false,
        ]);
    }

    /** @test */
    public function it_returns_404_when_storing_with_nonexistent_user()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->postJson("/api/v1/users/999/lessons/{$lesson->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_storing_with_nonexistent_lesson()
    {
        $user = User::factory()->create();

        $response = $this->postJson("/api/v1/users/{$user->id}/lessons/999");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_deletes_user_lesson_softly()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $userLesson = UserLesson::factory()->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}/soft");

        $response->assertStatus(204);

        $this->assertSoftDeleted('user_lessons', [
            'id' => $userLesson->id,
        ]);
    }

    /** @test */
    public function it_deletes_user_lesson_hard()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $userLesson = UserLesson::factory()->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}/force");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_lessons', [
            'id' => $userLesson->id,
        ]);
    }

    /** @test */
    public function it_restores_soft_deleted_user_lesson()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();
        $userLesson = UserLesson::factory()->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ]);
        $userLesson->delete();

        $response = $this->patchJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}/restore");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'userId',
                'lessonId',
                'courseId',
                'progress',
                'isCompleted',
                'completedAt'
            ]);

        $this->assertDatabaseHas('user_lessons', [
            'id' => $userLesson->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_returns_404_when_restoring_nonexistent_user_lesson()
    {
        $response = $this->patchJson("/api/v1/users/999/lessons/999/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_user_lesson_soft()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}/soft");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_user_lesson_hard()
    {
        $user = User::factory()->create();
        $lesson = Lesson::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}/force");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_prevents_duplicate_user_lesson_creation()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        UserLesson::factory()->create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'course_id' => $course->id,
        ]);

        $response = $this->postJson("/api/v1/users/{$user->id}/lessons/{$lesson->id}");

        $response->assertStatus(422);
    }

    /** @test */
    public function it_returns_user_lessons_with_completed_status()
    {
        $completedUserLesson = UserLesson::factory()->create([
            'is_completed' => true,
            'completed_at' => now(),
            'progress' => 100
        ]);
        $inProgressUserLesson = UserLesson::factory()->create([
            'is_completed' => false,
            'completed_at' => null,
            'progress' => 50
        ]);

        $response = $this->getJson('/api/v1/users/lessons');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'id' => $completedUserLesson->id,
                'isCompleted' => true,
                'progress' => 100
            ])
            ->assertJsonFragment([
                'id' => $inProgressUserLesson->id,
                'isCompleted' => false,
                'progress' => 50
            ]);
    }

    /** @test */
    public function it_filters_by_completed_lessons_only()
    {
        UserLesson::factory()->create(['is_completed' => true]);
        UserLesson::factory()->create(['is_completed' => true]);
        UserLesson::factory()->create(['is_completed' => false]);

        $response = $this->getJson('/api/v1/users/lessons?isCompleted=true');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_filters_by_in_progress_lessons_only()
    {
        UserLesson::factory()->create(['is_completed' => false, 'progress' => 50]);
        UserLesson::factory()->create(['is_completed' => false, 'progress' => 30]);
        UserLesson::factory()->create(['is_completed' => true]);

        $response = $this->getJson('/api/v1/users/lessons?isCompleted=false');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_handles_empty_results_with_filters()
    {
        UserLesson::factory()->create(['user_id' => 1]);

        $response = $this->getJson('/api/v1/users/lessons?user=999');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_validates_user_filter_as_positive_integer()
    {
        $response = $this->getJson('/api/v1/users/lessons?user=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user']);

        $response = $this->getJson('/api/v1/users/lessons?user=-1');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user']);
    }

    /** @test */
    public function it_validates_course_filter_as_positive_integer()
    {
        $response = $this->getJson('/api/v1/users/lessons?course=abc');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);
    }

    /** @test */
    public function it_validates_lesson_filter_as_positive_integer()
    {
        $response = $this->getJson('/api/v1/users/lessons?lesson=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['lesson']);
    }

    /** @test */
    public function it_returns_correct_metadata_with_filters()
    {
        UserLesson::factory()->count(15)->create(['user_id' => 1]);
        UserLesson::factory()->count(5)->create(['user_id' => 2]);

        $response = $this->getJson('/api/v1/users/lessons?user=1&size=5');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 15,
                    'perPage' => 5,
                    'lastPage' => 3,
                    'currentPage' => 1,
                ]
            ])
            ->assertJsonCount(5, 'data');
    }
}