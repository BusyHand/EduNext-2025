<?php

namespace Modules\Core\Tests\Feature\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\Course;
use Tests\TestCase;

class CourseControllerIntegrationTest extends TestCase
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
    public function it_stores_course_successfully()
    {
        $courseData = [
            'title' => 'Integration Test Course',
            'description' => 'Integration Test Description',
            'isPublished' => true,
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'description',
                'isPublished',
                'publishedAt',
                'ownerId',
                'createdBy',
                'updatedBy'
            ])
            ->assertJson([
                'title' => 'Integration Test Course',
                'description' => 'Integration Test Description',
                'isPublished' => true,
                'ownerId' => $this->user->id,
                'createdBy' => $this->user->id,
                'updatedBy' => $this->user->id,
            ]);
        $this->assertNotNull($response->json('publishedAt'));

        $this->assertDatabaseHas('courses', [
            'title' => 'Integration Test Course',
            'description' => 'Integration Test Description',
            'is_published' => true,
            'created_by' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_stores_course_with_minimal_data()
    {
        $courseData = [
            'title' => 'Minimal Course',
            'isPublished' => false,
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Minimal Course',
                'description' => null,
                'isPublished' => false,
                'ownerId' => $this->user->id,
                'createdBy' => $this->user->id,
                'updatedBy' => $this->user->id,
            ]);
        $this->assertNull($response->json('publishedAt'));

        $this->assertDatabaseHas('courses', [
            'title' => 'Minimal Course',
            'description' => null,
            'is_published' => false,
        ]);
    }

    /** @test */
    public function it_validates_required_title()
    {
        $courseData = [
            'description' => 'Missing title',
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('courses', 0);
    }

    /** @test */
    public function it_validates_title_max_length()
    {
        $courseData = [
            'title' => str_repeat('a', 256),
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('courses', 0);
    }

    /** @test */
    public function it_validates_is_published_as_boolean()
    {
        $courseData = [
            'title' => 'Test Course',
            'isPublished' => 'not-a-boolean',
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['isPublished']);

        $this->assertDatabaseCount('courses', 0);
    }

    /** @test */
    public function it_returns_all_courses_with_pagination()
    {
        Course::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/courses?page=2&size=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'description']
                ],
                'meta' => [
                    'currentPage',
                    'perPage',
                    'total',
                    'lastPage'
                ]
            ])
            ->assertJson([
                'meta' => [
                    'currentPage' => 2,
                    'perPage' => 10,
                    'total' => 25,
                    'lastPage' => 3,
                    'from' => 11,
                    'to' => 20,
                ],
            ]);

        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function it_filters_courses_by_title()
    {
        Course::factory()->create(['title' => 'Laravel Course']);
        Course::factory()->create(['title' => 'React Course']);
        Course::factory()->create(['title' => 'Vue.js Course']);

        $response = $this->getJson('/api/v1/courses?title=laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Laravel Course']);
    }

    /** @test */
    public function it_deletes_course_softly()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/v1/courses/{$course->id}/soft");

        $response->assertStatus(204);

        $this->assertSoftDeleted('courses', [
            'id' => $course->id,
            'deleted_by' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_restores_soft_deleted_course()
    {
        $course = Course::factory()->create();
        $course->delete();

        $response = $this->patchJson("/api/v1/courses/{$course->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'deleted_at' => null,
            'deleted_by' => null,
        ]);
    }

    /** @test */
    public function it_returns_course_by_id()
    {
        $course = Course::factory()->create();

        $response = $this->getJson("/api/v1/courses/{$course->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $course->id,
                'title' => $course->title,
                'description' => $course->description,
                'isPublished' => $course->is_published,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_course()
    {
        $response = $this->getJson("/api/v1/courses/999");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_updates_course_partially()
    {
        $course = Course::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
            'is_published' => false,
        ]);

        $updateData = [
            'title' => 'Partially Updated Title',
        ];

        $response = $this->patchJson("/api/v1/courses/{$course->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $course->id,
                'title' => 'Partially Updated Title',
                'description' => 'Original Description', // Should remain unchanged
                'isPublished' => false, // Should remain unchanged
                'updatedBy' => $this->user->id,
            ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Partially Updated Title',
            'description' => 'Original Description',
            'is_published' => false,
        ]);
    }

    /** @test */
    public function it_updates_only_is_published_field()
    {
        $course = Course::factory()->create([
            'title' => 'Original Title',
            'is_published' => false,
        ]);

        $updateData = [
            'isPublished' => true,
        ];

        $response = $this->patchJson("/api/v1/courses/{$course->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $course->id,
                'title' => 'Original Title', // Should remain unchanged
                'isPublished' => true,
            ]);

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'title' => 'Original Title',
            'is_published' => true,
        ]);
    }

    /** @test */
    public function it_returns_404_when_partially_updating_nonexistent_course()
    {
        $updateData = [
            'title' => 'Updated Title',
        ];

        $response = $this->patchJson("/api/v1/courses/999", $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_deletes_course_hard()
    {
        $course = Course::factory()->create();

        $response = $this->deleteJson("/api/v1/courses/{$course->id}/force");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('courses', [
            'id' => $course->id,
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_course_soft()
    {
        $response = $this->deleteJson("/api/v1/courses/999/soft");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_course_hard()
    {
        $response = $this->deleteJson("/api/v1/courses/999/force");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_restoring_nonexistent_course()
    {
        $response = $this->patchJson("/api/v1/courses/999/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_restoring_already_active_course()
    {
        $course = Course::factory()->create();

        $response = $this->patchJson("/api/v1/courses/{$course->id}/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_filters_courses_by_is_published()
    {
        Course::factory()->create(['title' => 'Published Course', 'is_published' => true]);
        Course::factory()->create(['title' => 'Draft Course', 'is_published' => false]);
        Course::factory()->create(['title' => 'Another Published', 'is_published' => true]);

        $response = $this->getJson('/api/v1/courses?isPublished=true');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Published Course'])
            ->assertJsonFragment(['title' => 'Another Published'])
            ->assertJsonMissing(['title' => 'Draft Course']);
    }

    /** @test */
    public function it_sorts_courses_by_title_asc()
    {
        Course::factory()->create(['title' => 'Z Course']);
        Course::factory()->create(['title' => 'A Course']);
        Course::factory()->create(['title' => 'M Course']);

        $response = $this->getJson('/api/v1/courses?sort=title,asc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('A Course', $data[0]['title']);
        $this->assertEquals('M Course', $data[1]['title']);
        $this->assertEquals('Z Course', $data[2]['title']);
    }

    /** @test */
    public function it_sorts_courses_by_created_at_desc()
    {
        $course1 = Course::factory()->create(['title' => 'First', 'created_at' => now()->subDays(2)]);
        $course2 = Course::factory()->create(['title' => 'Second', 'created_at' => now()->subDays(1)]);
        $course3 = Course::factory()->create(['title' => 'Third', 'created_at' => now()]);

        $response = $this->getJson('/api/v1/courses?sort=created_at,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('Third', $data[0]['title']);
        $this->assertEquals('Second', $data[1]['title']);
        $this->assertEquals('First', $data[2]['title']);
    }

    public function it_filters_courses_by_created_date_range()
    {
        $oldCourse = Course::factory()->create(['created_at' => '2024-01-01 00:00:00']);
        $recentCourse = Course::factory()->create(['created_at' => '2024-02-01 00:00:00']);
        $newCourse = Course::factory()->create(['created_at' => '2024-03-01 00:00:00']);

        $response = $this->getJson('/api/v1/courses?createdAfter=2024-01-15&createdBefore=2024-02-15');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $recentCourse->title])
            ->assertJsonMissing(['title' => $oldCourse->title])
            ->assertJsonMissing(['title' => $newCourse->title]);
    }

    /** @test */
    public function it_filters_courses_by_owner_id()
    {
        $otherUser = User::factory()->create();
        $userCourse = Course::factory()->create(['owner_id' => $this->user->id]);
        $otherCourse = Course::factory()->create(['owner_id' => $otherUser->id]);

        $response = $this->getJson("/api/v1/courses?owner={$this->user->id}");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $userCourse->title])
            ->assertJsonMissing(['title' => $otherCourse->title]);
    }

    /** @test */
    public function it_combines_multiple_filters()
    {
        Course::factory()->create([
            'title' => 'Laravel Course',
            'is_published' => true,
            'owner_id' => $this->user->id,
            'created_at' => '2024-02-01'
        ]);
        Course::factory()->create([
            'title' => 'React Course',
            'is_published' => false,
            'owner_id' => $this->user->id
        ]);
        Course::factory()->create([
            'title' => 'Vue Course',
            'is_published' => true,
            'owner_id' => User::factory()->create()->id
        ]);

        $response = $this->getJson('/api/v1/courses?title=laravel&isPublished=true&owner=' . $this->user->id);

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Laravel Course']);
    }

    /** @test */
    public function it_returns_empty_results_when_no_filters_match()
    {
        Course::factory()->create(['title' => 'Laravel Course']);
        Course::factory()->create(['title' => 'React Course']);

        $response = $this->getJson('/api/v1/courses?title=vue');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_validates_invalid_date_format_for_created_after()
    {
        $response = $this->getJson('/api/v1/courses?createdAfter=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdAfter']);
    }

    /** @test */
    public function it_validates_invalid_date_format_for_created_before()
    {
        $response = $this->getJson('/api/v1/courses?createdBefore=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdBefore']);
    }

    /** @test */
    public function it_validates_owner_is_positive_integer()
    {
        $response = $this->getJson('/api/v1/courses?owner=-1');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['owner']);
    }

    /** @test */
    public function it_handles_case_insensitive_title_filter()
    {
        Course::factory()->create(['title' => 'LARAVEL Course']);
        Course::factory()->create(['title' => 'laravel course']);
        Course::factory()->create(['title' => 'React Course']);

        $response = $this->getJson('/api/v1/courses?title=LaRaVeL');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_paginates_with_custom_per_page()
    {
        Course::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/courses?size=5');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'perPage' => 5,
                    'total' => 15,
                    'lastPage' => 3,
                ]
            ])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_uses_default_pagination_when_invalid_size_provided()
    {
        Course::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/courses?size=invalid');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'meta' => ['currentPage', 'perPage', 'total', 'lastPage']
            ]);
    }

    /** @test */
    public function it_sorts_by_multiple_columns()
    {
        Course::factory()->create(['title' => 'B Course', 'created_at' => '2024-01-01']);
        Course::factory()->create(['title' => 'A Course', 'created_at' => '2024-01-02']);
        Course::factory()->create(['title' => 'B Course', 'created_at' => '2024-01-03']);

        $response = $this->getJson('/api/v1/courses?sort=title,created_at,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('A Course', $data[0]['title']);
        $this->assertEquals('B Course', $data[1]['title']);
        $this->assertEquals('2024-01-03', Carbon::parse($data[1]['createdAt'])->format('Y-m-d'));
        $this->assertEquals('B Course', $data[2]['title']);
        $this->assertEquals('2024-01-01', Carbon::parse($data[2]['createdAt'])->format('Y-m-d'));
    }

    /** @test */
    public function it_handles_invalid_sort_parameters_gracefully()
    {
        Course::factory()->create(['title' => 'Test Course']);

        $response = $this->getJson('/api/v1/courses?sort=invalid_column,desc');

        $response->assertStatus(422);
    }

    /** @test */
    public function it_creates_course_with_special_characters_in_title()
    {
        $courseData = [
            'title' => 'Course with spéciål chàräctérs & symbols! @#$%',
            'isPublished' => true,
        ];

        $response = $this->postJson('/api/v1/courses', $courseData);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Course with spéciål chàräctérs & symbols! @#$%']);

        $this->assertDatabaseHas('courses', [
            'title' => 'Course with spéciål chàräctérs & symbols! @#$%',
        ]);
    }

    /** @test */
    public function it_handles_concurrent_updates_correctly()
    {
        $course = Course::factory()->create([
            'title' => 'Original Title',
            'description' => 'Original Description',
        ]);

        // Simulate concurrent updates
        $update1 = ['title' => 'First Update'];
        $update2 = ['description' => 'Second Update'];

        $response1 = $this->patchJson("/api/v1/courses/{$course->id}", $update1);
        $response2 = $this->patchJson("/api/v1/courses/{$course->id}", $update2);

        $response1->assertStatus(200);
        $response2->assertStatus(200);

        $finalResponse = $this->getJson("/api/v1/courses/{$course->id}");
        $finalResponse->assertJson([
            'title' => 'First Update',
            'description' => 'Second Update',
        ]);
    }

    /** @test */
    public function it_returns_correct_metadata_with_filters()
    {
        Course::factory()->count(8)->create(['is_published' => true]);
        Course::factory()->count(4)->create(['is_published' => false]);

        $response = $this->getJson('/api/v1/courses?isPublished=true&size=5');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 8,
                    'perPage' => 5,
                    'lastPage' => 2,
                    'currentPage' => 1,
                ]
            ])
            ->assertJsonCount(5, 'data');
    }

    /** @test */
    public function it_searches_by_partial_title_match()
    {
        Course::factory()->create(['title' => 'Advanced Laravel Programming']);
        Course::factory()->create(['title' => 'Laravel for Beginners']);
        Course::factory()->create(['title' => 'React Native Guide']);

        $response = $this->getJson('/api/v1/courses?title=laravel');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }
}
