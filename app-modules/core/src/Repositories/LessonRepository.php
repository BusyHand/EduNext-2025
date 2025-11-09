<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Http\Filters\LessonFilter;
use Modules\Core\Models\Course;
use Modules\Core\Models\Lesson;

class LessonRepository
{
    use Pageable;

    public function findAll(LessonFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        $query = $filterQuery->apply(Lesson::published());
        return $this->paginate($query, $pageableData);
    }

    public function store(Lesson $lessonToSave): Lesson
    {
        $lessonToSave->save();
        return $lessonToSave;
    }

    public function updatePartial(Lesson $oldLesson, Lesson $newLesson): Lesson
    {
        $filledData = array_filter($newLesson->toArray(), fn($value) => !is_null($value));
        $oldLesson->update($filledData);
        return $oldLesson->fresh();
    }

    public function restore(int $lessonId): Lesson
    {
        $affected = Lesson::withTrashed()
            ->where('id', $lessonId)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
                'deleted_by' => null,
            ]);

        if ($affected === 0) {
            throw new ModelNotFoundException("Course not found or already restored");
        }

        return Lesson::findOrFail($lessonId);
    }

    public function deleteSoft(Lesson $lesson): bool
    {
        return $lesson->delete();
    }

    public function deleteHard(Lesson $lesson): bool
    {
        return $lesson->forceDelete();
    }
}
