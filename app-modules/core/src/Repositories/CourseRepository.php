<?php

namespace Modules\Core\Repositories;

use App\Http\Data\PageableData;
use App\Traits\Pageable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Models\Course;

class CourseRepository
{
    use Pageable;

    public function findAll(CourseFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        $query = $filterQuery->apply(Course::published());
        return $this->paginate($query, $pageableData);
    }

    public function store(Course $courseToSave): Course
    {
        $courseToSave->save();
        return $courseToSave;
    }

    public function update(Course $oldCourse, Course $newCourse): Course
    {
        $oldCourse->update($newCourse->toArray());
        return $oldCourse->refresh();
    }

    public function updatePartial(Course $oldCourse, Course $newCourse): Course
    {
        $filledData = array_filter($newCourse->toArray(), fn($value) => !is_null($value));
        $oldCourse->update($filledData);
        return $oldCourse->fresh();
    }

    public function restore(int $courseId): Course
    {
        $affected = Course::withTrashed()
            ->where('id', $courseId)
            ->whereNotNull('deleted_at')
            ->update([
                'deleted_at' => null,
                'deleted_by' => null,
            ]);

        if ($affected === 0) {
            throw new ModelNotFoundException("Course not found or already restored");
        }

        return Course::findOrFail($courseId);
    }

    public function deleteSoft(Course $course): bool
    {
        return $course->delete();
    }

    public function deleteHard(Course $course): bool
    {
        return $course->forceDelete();
    }
}
