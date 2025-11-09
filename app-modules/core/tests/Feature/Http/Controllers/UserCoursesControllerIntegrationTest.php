<?php

namespace Modules\Core\Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\Course;
use Modules\Core\Models\UserCourse;
use Tests\TestCase;

class UserCoursesControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function it_returns_all_user_courses_with_pagination()
    {
        UserCourse::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/users/courses?page=2&size=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'userId', 'courseId']
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
    public function it_filters_user_courses_by_user_id()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        UserCourse::factory()->create(['user_id' => $user1->id]);
        UserCourse::factory()->create(['user_id' => $user1->id]);
        UserCourse::factory()->create(['user_id' => $user2->id]);

        $response = $this->getJson("/api/v1/users/courses?user={$user1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_filters_user_courses_by_course_id()
    {
        $course1 = Course::factory()->create();
        $course2 = Course::factory()->create();

        UserCourse::factory()->create(['course_id' => $course1->id]);
        UserCourse::factory()->create(['course_id' => $course1->id]);
        UserCourse::factory()->create(['course_id' => $course2->id]);

        $response = $this->getJson("/api/v1/users/courses?course={$course1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_combines_multiple_filters()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
        UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => Course::factory()->create()->id
        ]);
        UserCourse::factory()->create([
            'user_id' => User::factory()->create()->id,
            'course_id' => $course->id
        ]);

        $response = $this->getJson("/api/v1/users/courses?user={$user->id}&course={$course->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }

    /** @test */
    public function it_sorts_user_courses_by_created_at_desc()
    {
        $userCourse1 = UserCourse::factory()->create(['created_at' => now()->subDays(2)]);
        $userCourse2 = UserCourse::factory()->create(['created_at' => now()->subDays(1)]);
        $userCourse3 = UserCourse::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/v1/users/courses?sort=created_at,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($userCourse3->id, $data[0]['id']);
        $this->assertEquals($userCourse2->id, $data[1]['id']);
        $this->assertEquals($userCourse1->id, $data[2]['id']);
    }

    /** @test */
    public function it_sorts_user_courses_by_created_at_asc()
    {
        $userCourse1 = UserCourse::factory()->create(['created_at' => now()->subDays(2)]);
        $userCourse2 = UserCourse::factory()->create(['created_at' => now()->subDays(1)]);
        $userCourse3 = UserCourse::factory()->create(['created_at' => now()]);

        $response = $this->getJson('/api/v1/users/courses?sort=created_at,asc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals($userCourse1->id, $data[0]['id']);
        $this->assertEquals($userCourse2->id, $data[1]['id']);
        $this->assertEquals($userCourse3->id, $data[2]['id']);
    }

    /** @test */
    public function it_stores_user_course_successfully()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $response = $this->postJson("/api/v1/users/{$user->id}/courses/{$course->id}");

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'userId',
                'courseId'
            ])
            ->assertJson([
                'userId' => $user->id,
                'courseId' => $course->id,
            ]);

        $this->assertDatabaseHas('user_courses', [
            'user_id' => $user->id,
            'course_id' => $course->id,
        ]);
    }

    /** @test */
    public function it_returns_404_when_storing_with_nonexistent_user()
    {
        $course = Course::factory()->create();

        $response = $this->postJson("/api/v1/users/999/courses/{$course->id}");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_storing_with_nonexistent_course()
    {
        $user = User::factory()->create();

        $response = $this->postJson("/api/v1/users/{$user->id}/courses/999");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_deletes_user_course_softly()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $userCourse = UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}/courses/{$course->id}/soft");

        $response->assertStatus(204);

        $this->assertSoftDeleted('user_courses', [
            'id' => $userCourse->id,
        ]);
    }

    /** @test */
    public function it_deletes_user_course_hard()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $userCourse = UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}/courses/{$course->id}/force");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('user_courses', [
            'id' => $userCourse->id,
        ]);
    }

    /** @test */
    public function it_restores_soft_deleted_user_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $userCourse = UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);
        $userCourse->delete();

        $response = $this->patchJson("/api/v1/users/{$user->id}/courses/{$course->id}/restore");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'userId',
                'courseId'
            ]);

        $this->assertDatabaseHas('user_courses', [
            'id' => $userCourse->id,
            'deleted_at' => null,
        ]);
    }

    /** @test */
    public function it_returns_404_when_restoring_nonexistent_user_course()
    {
        $response = $this->patchJson("/api/v1/users/999/courses/999/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_user_course_soft()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}/courses/{$course->id}/soft");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_user_course_hard()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/v1/users/{$user->id}/courses/{$course->id}/force");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_prevents_duplicate_user_course_creation()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $response = $this->postJson("/api/v1/users/{$user->id}/courses/{$course->id}");

        $response->assertStatus(422);
    }

    /** @test */
    public function it_handles_empty_results_with_filters()
    {
        UserCourse::factory()->create(['user_id' => 1]);

        $response = $this->getJson('/api/v1/users/courses?user=999');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_validates_user_filter_as_positive_integer()
    {
        $response = $this->getJson('/api/v1/users/courses?user=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user']);

        $response = $this->getJson('/api/v1/users/courses?user=-1');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['user']);
    }

    /** @test */
    public function it_validates_course_filter_as_positive_integer()
    {
        $response = $this->getJson('/api/v1/users/courses?course=abc');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);

        $response = $this->getJson('/api/v1/users/courses?course=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);
    }

    /** @test */
    public function it_returns_correct_metadata_with_filters()
    {
        UserCourse::factory()->count(15)->create(['user_id' => 1]);
        UserCourse::factory()->count(5)->create(['user_id' => 2]);

        $response = $this->getJson('/api/v1/users/courses?user=1&size=5');

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

    /** @test */
    public function it_returns_404_when_restoring_already_active_user_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        $userCourse = UserCourse::factory()->create([
            'user_id' => $user->id,
            'course_id' => $course->id
        ]);

        $response = $this->patchJson("/api/v1/users/{$user->id}/courses/{$course->id}/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_handles_large_number_of_user_courses()
    {
        UserCourse::factory()->count(100)->create();

        $response = $this->getJson('/api/v1/users/courses?size=50');

        $response->assertStatus(200)
            ->assertJsonCount(50, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 100,
                    'perPage' => 50,
                    'lastPage' => 2,
                ]
            ]);
    }

    /** @test */
    public function it_returns_empty_list_when_no_user_courses_exist()
    {
        $response = $this->getJson('/api/v1/users/courses');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data')
            ->assertJson([
                'meta' => [
                    'total' => 0,
                    'perPage' => 15, // или ваш дефолтный размер
                    'lastPage' => 1,
                    'currentPage' => 1,
                ]
            ]);
    }
}