<?php

namespace Modules\Core\Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;
use Tests\TestCase;

class LessonControllerIntegrationTest extends TestCase
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
    public function it_stores_lesson_successfully()
    {
        $courseId = Course::factory()->create()->id;
        $lessonData = [
            'title' => 'Integration Test Lesson',
            'content' => 'Integration Test Content',
            'isPublished' => true,
            'courseId' => $courseId,
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'title',
                'content',
                'courseId',
                'isPublished',
                'publishedAt',
                'createdBy',
                'updatedBy'
            ])
            ->assertJson([
                'title' => 'Integration Test Lesson',
                'content' => 'Integration Test Content',
                'isPublished' => true,
                'courseId' => $courseId,
                'createdBy' => $this->user->id,
                'updatedBy' => $this->user->id,
            ]);
        $this->assertNotNull($response->json('publishedAt'));

        $this->assertDatabaseHas('lessons', [
            'title' => 'Integration Test Lesson',
            'content' => 'Integration Test Content',
            'is_published' => true,
            'course_id' => $courseId,
            'created_by' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_stores_lesson_with_minimal_data()
    {
        $courseId = Course::factory()->create()->id;
        $lessonData = [
            'title' => 'Minimal Lessonsaasdfsadf',
            'content' => 'Minimal Lessonsaasdfsadf',
            'isPublished' => false,
            'courseId' => $courseId,
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Minimal Lessonsaasdfsadf',
                'content' => 'Minimal Lessonsaasdfsadf',
                'isPublished' => false,
                'courseId' => $courseId,
                'createdBy' => $this->user->id,
                'updatedBy' => $this->user->id,
            ]);
        $this->assertNull($response->json('publishedAt'));

        $this->assertDatabaseHas('lessons', [
            'title' => 'Minimal Lessonsaasdfsadf',
            'content' => 'Minimal Lessonsaasdfsadf',
            'is_published' => false,
            'course_id' => $courseId,
        ]);
    }

    /** @test */
    public function it_validates_required_title()
    {
        $lessonData = [
            'description' => 'Missing title',
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('lessons', 0);
    }

    /** @test */
    public function it_validates_title_max_length()
    {
        $lessonData = [
            'title' => str_repeat('a', 256),
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);

        $this->assertDatabaseCount('lessons', 0);
    }

    /** @test */
    public function it_validates_is_published_as_boolean()
    {
        $lessonData = [
            'title' => 'Test Lesson',
            'isPublished' => 'not-a-boolean',
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['isPublished']);

        $this->assertDatabaseCount('lessons', 0);
    }

    /** @test */
    public function it_returns_all_lessons_with_pagination()
    {
        Lesson::factory()->count(25)->create();

        $response = $this->getJson('/api/v1/lessons?page=2&size=10');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title', 'content']
                ],
                'meta' => [
                    'currentPage',
                    'perPage',
                    'total',
                    'lastPage',
                    'from',
                    'to',
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
    public function it_filters_lessons_by_title()
    {
        Lesson::factory()->create(['title' => 'Laravel Lesson']);
        Lesson::factory()->create(['title' => 'React Lesson']);
        Lesson::factory()->create(['title' => 'Vue.js Lesson']);

        $response = $this->getJson('/api/v1/lessons?title=laravel');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Laravel Lesson']);
    }

    /** @test */
    public function it_filters_lessons_by_is_published()
    {
        Lesson::factory()->create(['title' => 'Published Lesson', 'is_published' => true]);
        Lesson::factory()->create(['title' => 'Draft Lesson', 'is_published' => false]);
        Lesson::factory()->create(['title' => 'Another Published', 'is_published' => true]);

        $response = $this->getJson('/api/v1/lessons?isPublished=true');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Published Lesson'])
            ->assertJsonFragment(['title' => 'Another Published'])
            ->assertJsonMissing(['title' => 'Draft Lesson']);
    }

    /** @test */
    public function it_updates_lesson_successfully()
    {
        $lesson = Lesson::factory()->create([
            'title' => 'Old Title',
            'content' => 'Old Content',
            'is_published' => false,
        ]);

        $updateData = [
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'isPublished' => true,
        ];

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $lesson->id,
                'title' => 'Updated Title',
                'content' => 'Updated Content',
                'isPublished' => true,
                'updatedBy' => $this->user->id,
            ]);
        $this->assertNotNull($response->json('publishedAt'));

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Updated Title',
            'content' => 'Updated Content',
            'is_published' => true,
            'updated_by' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_updates_lesson_partially()
    {
        $lesson = Lesson::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original Content',
            'is_published' => false,
        ]);

        $updateData = [
            'title' => 'Partially Updated Title',
        ];

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $lesson->id,
                'title' => 'Partially Updated Title',
                'content' => 'Original Content', // Should remain unchanged
                'isPublished' => false, // Should remain unchanged
                'updatedBy' => $this->user->id,
            ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Partially Updated Title',
            'content' => 'Original Content',
            'is_published' => false,
        ]);
    }

    /** @test */
    public function it_updates_only_is_published_field()
    {
        $lesson = Lesson::factory()->create([
            'title' => 'Original Title',
            'is_published' => false,
        ]);

        $updateData = [
            'isPublished' => true,
        ];

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $lesson->id,
                'title' => 'Original Title', // Should remain unchanged
                'isPublished' => true,
            ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Original Title',
            'is_published' => true,
        ]);
    }

    /** @test */
    public function it_returns_lesson_by_id()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->getJson("/api/v1/lessons/{$lesson->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $lesson->id,
                'title' => $lesson->title,
                'content' => $lesson->content,
                'isPublished' => $lesson->is_published,
            ]);
    }

    /** @test */
    public function it_returns_404_for_nonexistent_lesson()
    {
        $response = $this->getJson("/api/v1/lessons/999");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_deletes_lesson_softly()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->deleteJson("/api/v1/lessons/{$lesson->id}/soft");

        $response->assertStatus(204);

        $this->assertSoftDeleted('lessons', [
            'id' => $lesson->id,
            'deleted_by' => $this->user->id,
        ]);
    }

    /** @test */
    public function it_deletes_lesson_hard()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->deleteJson("/api/v1/lessons/{$lesson->id}/force");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('lessons', [
            'id' => $lesson->id,
        ]);
    }

    /** @test */
    public function it_restores_soft_deleted_lesson()
    {
        $lesson = Lesson::factory()->create();
        $lesson->delete();

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}/restore");

        $response->assertStatus(200);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'deleted_at' => null,
            'deleted_by' => null,
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_lesson()
    {
        $updateData = [
            'title' => 'Updated Title',
        ];

        $response = $this->patchJson("/api/v1/lessons/999", $updateData);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_lesson_soft()
    {
        $response = $this->deleteJson("/api/v1/lessons/999/soft");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_lesson_hard()
    {
        $response = $this->deleteJson("/api/v1/lessons/999/force");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_restoring_nonexistent_lesson()
    {
        $response = $this->patchJson("/api/v1/lessons/999/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_404_when_restoring_already_active_lesson()
    {
        $lesson = Lesson::factory()->create();

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}/restore");

        $response->assertStatus(404);
    }

    /** @test */
    public function it_sorts_lessons_by_title_asc()
    {
        Lesson::factory()->create(['title' => 'Z Lesson']);
        Lesson::factory()->create(['title' => 'A Lesson']);
        Lesson::factory()->create(['title' => 'M Lesson']);

        $response = $this->getJson('/api/v1/lessons?sort=title,asc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('A Lesson', $data[0]['title']);
        $this->assertEquals('M Lesson', $data[1]['title']);
        $this->assertEquals('Z Lesson', $data[2]['title']);
    }

    /** @test */
    public function it_sorts_lessons_by_created_at_desc()
    {
        $lesson1 = Lesson::factory()->create(['title' => 'First', 'created_at' => now()->subDays(2)]);
        $lesson2 = Lesson::factory()->create(['title' => 'Second', 'created_at' => now()->subDays(1)]);
        $lesson3 = Lesson::factory()->create(['title' => 'Third', 'created_at' => now()]);

        $response = $this->getJson('/api/v1/lessons?sort=created_at,desc');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('Third', $data[0]['title']);
        $this->assertEquals('Second', $data[1]['title']);
        $this->assertEquals('First', $data[2]['title']);
    }

    /** @test */
    public function it_filters_lessons_by_created_date_range()
    {
        $oldLesson = Lesson::factory()->create(['created_at' => '2024-01-01 00:00:00']);
        $recentLesson = Lesson::factory()->create(['created_at' => '2024-02-01 00:00:00']);
        $newLesson = Lesson::factory()->create(['created_at' => '2024-03-01 00:00:00']);

        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-01-15&createdBefore=2024-02-15');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $recentLesson->title])
            ->assertJsonMissing(['title' => $oldLesson->title])
            ->assertJsonMissing(['title' => $newLesson->title]);
    }

    /** @test */
    public function it_creates_lesson_with_special_characters_in_title()
    {
        $courseId = Course::factory()->create()->id;
        $lessonData = [
            'title' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
            'content' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
            'isPublished' => true,
            'courseId' => $courseId,
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'title' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
                'content' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
                'courseId' => $courseId,
            ]);

        $this->assertDatabaseHas('lessons', [
            'title' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
            'content' => 'Lesson with spéciål chàräctérs & symbols! @#$%',
            'course_id' => $courseId,
        ]);
    }

    /** @test */
    public function it_returns_empty_results_when_no_filters_match()
    {
        Lesson::factory()->create(['title' => 'Laravel Lesson']);
        Lesson::factory()->create(['title' => 'React Lesson']);

        $response = $this->getJson('/api/v1/lessons?title=vue');

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_handles_case_insensitive_title_filter()
    {
        Lesson::factory()->create(['title' => 'LARAVEL Lesson']);
        Lesson::factory()->create(['title' => 'laravel lesson']);
        Lesson::factory()->create(['title' => 'React Lesson']);

        $response = $this->getJson('/api/v1/lessons?title=LaRaVeL');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_updates_lesson_with_empty_description()
    {
        $lesson = Lesson::factory()->create([
            'title' => 'Original Title',
        ]);

        $updateData = [
            'title' => 'Updated Title',
        ];

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
            ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'title' => 'Updated Title',
        ]);
    }

    /** @test */
    public function it_filters_lessons_by_multiple_criteria_combined()
    {
        $publishedLesson = Lesson::factory()->create([
            'title' => 'Laravel Advanced',
            'is_published' => true,
            'created_at' => '2024-02-15 10:00:00'
        ]);

        $draftLesson = Lesson::factory()->create([
            'title' => 'Laravel Basics',
            'is_published' => false,
            'created_at' => '2024-02-10 10:00:00'
        ]);

        $otherLesson = Lesson::factory()->create([
            'title' => 'React Tutorial',
            'is_published' => true,
            'created_at' => '2024-02-20 10:00:00'
        ]);

        $response = $this->getJson('/api/v1/lessons?title=laravel&isPublished=true&createdAfter=2024-02-12');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Laravel Advanced'])
            ->assertJsonMissing(['title' => 'Laravel Basics'])
            ->assertJsonMissing(['title' => 'React Tutorial']);
    }

    /** @test */
    public function it_paginates_with_custom_per_page_values()
    {
        Lesson::factory()->count(35)->create();

        $response = $this->getJson('/api/v1/lessons?size=15');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'perPage' => 15,
                    'total' => 35,
                    'lastPage' => 3,
                ]
            ])
            ->assertJsonCount(15, 'data');
    }

    /** @test */
    public function it_sorts_by_multiple_columns_simultaneously()
    {
        Lesson::factory()->create(['title' => 'C Lesson', 'created_at' => '2024-01-03']);
        Lesson::factory()->create(['title' => 'A Lesson', 'created_at' => '2024-01-02']);
        Lesson::factory()->create(['title' => 'B Lesson', 'created_at' => '2024-01-01']);

        $response = $this->getJson('/api/v1/lessons?sort=title');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertEquals('A Lesson', $data[0]['title']);
        $this->assertEquals('B Lesson', $data[1]['title']);
        $this->assertEquals('C Lesson', $data[2]['title']);
    }

    /** @test */
    public function it_handles_complex_search_with_special_characters()
    {
        Lesson::factory()->create(['title' => 'C# Programming']);
        Lesson::factory()->create(['title' => 'C++ Basics']);
        Lesson::factory()->create(['title' => 'Python 3.11']);

        $response = $this->getJson('/api/v1/lessons?title=Python');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Python 3.11']);
    }

    /** @test */
    public function it_validates_content_max_length()
    {
        $lessonData = [
            'title' => 'Test Lesson',
            'content' => str_repeat('a', 10001),
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content']);
    }

    /** @test */
    public function it_updates_only_content_field()
    {
        $lesson = Lesson::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
        ]);

        $newContent = 'Updated content with new information and details.';

        $response = $this->patchJson("/api/v1/lessons/{$lesson->id}", [
            'content' => $newContent
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Original Title',
                'content' => $newContent,
            ]);

        $this->assertDatabaseHas('lessons', [
            'id' => $lesson->id,
            'content' => $newContent,
            'title' => 'Original Title',
        ]);
    }

    /** @test */
    public function it_returns_correct_metadata_with_complex_filters()
    {
        Lesson::factory()->count(12)->create(['is_published' => true]);
        Lesson::factory()->count(8)->create(['is_published' => false]);
        Lesson::factory()->count(5)->create(['title' => 'Laravel Tutorial', 'is_published' => true]);

        $response = $this->getJson('/api/v1/lessons?title=laravel&isPublished=true&size=3');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'total' => 5,
                    'perPage' => 3,
                    'lastPage' => 2,
                    'currentPage' => 1,
                ]
            ])
            ->assertJsonCount(3, 'data');
    }

    /** @test */
    public function it_handles_very_long_titles_correctly()
    {
        $longTitle = str_repeat('A', 255); // Maximum allowed length
        $courseId = Course::factory()->create()->id;
        $lessonData = [
            'title' => $longTitle,
            'content' => $longTitle,
            'isPublished' => true,
            'courseId' => $courseId,
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(201)
            ->assertJson([
                'title' => $longTitle,
            ]);

        $this->assertDatabaseHas('lessons', [
            'title' => $longTitle,
        ]);
    }

    /** @test */
    public function it_handles_mixed_published_and_draft_lessons_in_filters()
    {
        Lesson::factory()->create(['title' => 'Published 1', 'is_published' => true,]);
        Lesson::factory()->create(['title' => 'Draft 1', 'is_published' => false,]);
        Lesson::factory()->create(['title' => 'Published 2', 'is_published' => true,]);
        Lesson::factory()->create(['title' => 'Draft 2', 'is_published' => false,]);

        $response = $this->getJson('/api/v1/lessons');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');

        $data = $response->json('data');
        $this->assertEquals('Published 1', $data[0]['title']);
        $this->assertEquals('Published 2', $data[1]['title']);
    }

    /** @test */
    public function it_not_creates_lesson_with_very_long_content()
    {
        $longContent = str_repeat('This is a long content. ', 1000); // ~25,000 characters

        $lessonData = [
            'title' => 'Lesson with Long Content',
            'content' => $longContent,
        ];

        $response = $this->postJson('/api/v1/lessons', $lessonData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The content field must not be greater than 2000 characters. (and 1 more error)',
            ]);
    }

    /** @test */
    public function it_handles_duplicate_order_values_correctly()
    {
        Lesson::factory()->create(['title' => 'First',]);
        Lesson::factory()->create(['title' => 'Second',]);
        Lesson::factory()->create(['title' => 'Third',]);

        $response = $this->getJson('/api/v1/lessons?sort=title,asc');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $data = $response->json('data');
        $this->assertEquals('First', $data[0]['title']);
        $this->assertEquals('Second', $data[1]['title']);
        $this->assertEquals('Third', $data[2]['title']);
    }

    /** @test */
    public function it_validates_title_filter_required_when_provided()
    {
        $response = $this->getJson('/api/v1/lessons?title=');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_validates_title_filter_max_length()
    {
        $longTitle = str_repeat('a', 256);
        $response = $this->getJson('/api/v1/lessons?title=' . $longTitle);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title']);
    }

    /** @test */
    public function it_validates_course_filter_is_positive_integer()
    {
        $response = $this->getJson('/api/v1/lessons?course=0');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);

        $response = $this->getJson('/api/v1/lessons?course=-1');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);
    }

    /** @test */
    public function it_validates_course_filter_is_integer()
    {
        $response = $this->getJson('/api/v1/lessons?course=abc');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['course']);
    }

    /** @test */
    public function it_validates_created_after_date_format()
    {
        $response = $this->getJson('/api/v1/lessons?createdAfter=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdAfter']);

        $response = $this->getJson('/api/v1/lessons?createdAfter=2024/01/01');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdAfter']);
    }

    /** @test */
    public function it_validates_created_before_date_format()
    {
        $response = $this->getJson('/api/v1/lessons?createdBefore=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdBefore']);

        $response = $this->getJson('/api/v1/lessons?createdBefore=2024/01/01');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdBefore']);
    }

    /** @test */
    public function it_validates_created_after_before_created_before()
    {
        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-02-01&createdBefore=2024-01-01');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdAfter']);
    }

    /** @test */
    public function it_validates_created_before_after_created_after()
    {
        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-02-01&createdBefore=2024-01-01');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdBefore']);
    }

    /** @test */
    public function it_accepts_valid_date_range()
    {
        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-01-01&createdBefore=2024-02-01');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_filters_lessons_by_course_id()
    {
        $course1 = Course::factory()->create();
        $course2 = Course::factory()->create();

        Lesson::factory()->create(['title' => 'Lesson 1', 'course_id' => $course1->id]);
        Lesson::factory()->create(['title' => 'Lesson 2', 'course_id' => $course1->id]);
        Lesson::factory()->create(['title' => 'Lesson 3', 'course_id' => $course2->id]);

        $response = $this->getJson("/api/v1/lessons?course={$course1->id}");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Lesson 1'])
            ->assertJsonFragment(['title' => 'Lesson 2'])
            ->assertJsonMissing(['title' => 'Lesson 3']);
    }

    /** @test */
    public function it_returns_empty_when_course_has_no_lessons()
    {
        $course = Course::factory()->create();
        Lesson::factory()->create(['course_id' => Course::factory()->create()->id]);

        $response = $this->getJson("/api/v1/lessons?course={$course->id}");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_combines_course_filter_with_title_filter()
    {
        $course = Course::factory()->create();

        Lesson::factory()->create([
            'title' => 'Laravel Basics',
            'course_id' => $course->id
        ]);
        Lesson::factory()->create([
            'title' => 'Laravel Advanced',
            'course_id' => $course->id
        ]);
        Lesson::factory()->create([
            'title' => 'Laravel Basics',
            'course_id' => Course::factory()->create()->id
        ]);
        Lesson::factory()->create([
            'title' => 'React Tutorial',
            'course_id' => $course->id
        ]);

        $response = $this->getJson("/api/v1/lessons?course={$course->id}&title=laravel");

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment(['title' => 'Laravel Basics'])
            ->assertJsonFragment(['title' => 'Laravel Advanced'])
            ->assertJsonMissing(['title' => 'React Tutorial']);
    }

    /** @test */
    public function it_filters_lessons_by_created_date_range_with_course()
    {
        $course = Course::factory()->create();

        $lesson1 = Lesson::factory()->create([
            'course_id' => $course->id,
            'created_at' => '2024-01-15 10:00:00'
        ]);
        $lesson2 = Lesson::factory()->create([
            'course_id' => $course->id,
            'created_at' => '2024-02-15 10:00:00'
        ]);
        $lesson3 = Lesson::factory()->create([
            'course_id' => $course->id,
            'created_at' => '2024-03-15 10:00:00'
        ]);

        $response = $this->getJson("/api/v1/lessons?course={$course->id}&createdAfter=2024-01-20&createdBefore=2024-03-10");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $lesson2->title])
            ->assertJsonMissing(['title' => $lesson1->title])
            ->assertJsonMissing(['title' => $lesson3->title]);
    }

    /** @test */
    public function it_handles_single_date_filter_created_after()
    {
        Lesson::factory()->create(['created_at' => '2024-01-01']);
        Lesson::factory()->create(['created_at' => '2024-02-01']);
        Lesson::factory()->create(['created_at' => '2024-03-01']);

        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-01-15');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_handles_single_date_filter_created_before()
    {
        Lesson::factory()->create(['created_at' => '2024-01-01']);
        Lesson::factory()->create(['created_at' => '2024-02-01']);
        Lesson::factory()->create(['created_at' => '2024-03-01']);

        $response = $this->getJson('/api/v1/lessons?createdBefore=2024-02-15');

        $response->assertStatus(200)
            ->assertJsonCount(2, 'data');
    }

    /** @test */
    public function it_validates_course_exists_when_filtering()
    {
        $nonExistentCourseId = 9999;

        $response = $this->getJson("/api/v1/lessons?course={$nonExistentCourseId}");

        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /** @test */
    public function it_filters_with_exact_date_match()
    {
        $lesson = Lesson::factory()->create(['created_at' => '2024-02-15 10:00:00']);
        Lesson::factory()->create(['created_at' => '2024-02-16 10:00:00']);

        $response = $this->getJson('/api/v1/lessons?createdAfter=2024-02-15&createdBefore=2024-02-15');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $lesson->title]);
    }

    /** @test */
    public function it_accepts_valid_title_with_special_characters()
    {
        $response = $this->getJson('/api/v1/lessons?title=Lesson%20with%20spaces');

        $response->assertStatus(200);

        $response = $this->getJson('/api/v1/lessons?title=Lesson-with-dashes');

        $response->assertStatus(200);

        $response = $this->getJson('/api/v1/lessons?title=Lesson_with_underscores');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_mixed_valid_and_invalid_filters_gracefully()
    {
        Lesson::factory()->create(['title' => 'Valid Lesson']);

        $response = $this->getJson('/api/v1/lessons?title=Valid%20Lesson&createdAfter=invalid-date');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['createdAfter']);
    }

    /** @test */
    public function it_filters_by_course_and_date_range_together()
    {
        $course1 = Course::factory()->create();
        $course2 = Course::factory()->create();

        $lesson1 = Lesson::factory()->create([
            'course_id' => $course1->id,
            'created_at' => '2024-02-01'
        ]);
        $lesson2 = Lesson::factory()->create([
            'course_id' => $course1->id,
            'created_at' => '2024-03-01'
        ]);
        $lesson3 = Lesson::factory()->create([
            'course_id' => $course2->id,
            'created_at' => '2024-02-15'
        ]);

        $response = $this->getJson("/api/v1/lessons?course={$course1->id}&createdAfter=2024-01-15&createdBefore=2024-02-15");

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => $lesson1->title])
            ->assertJsonMissing(['title' => $lesson2->title])
            ->assertJsonMissing(['title' => $lesson3->title]);
    }

    /** @test */
    public function it_validates_empty_string_as_invalid_for_required_fields()
    {
        $response = $this->getJson('/api/v1/lessons?title=&course=');

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title', 'course']);
    }

    /** @test */
    public function it_accepts_numeric_string_for_course_filter()
    {
        $course = Course::factory()->create();

        $response = $this->getJson("/api/v1/lessons?course={$course->id}");

        $response->assertStatus(200);
    }

    /** @test */
    public function it_handles_whitespace_in_title_filter()
    {
        Lesson::factory()->create(['title' => 'Test Lesson']);
        Lesson::factory()->create(['title' => 'Another Lesson']);

        $response = $this->getJson('/api/v1/lessons?title=Test%20Lesson');

        $response->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment(['title' => 'Test Lesson']);
    }
}
