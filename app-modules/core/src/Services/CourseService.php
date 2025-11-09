<?php

namespace Modules\Core\Services;

use App\Http\Data\PageableData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Models\Course;
use Modules\Core\Repositories\CourseRepository;

readonly class CourseService
{
    public function __construct(
        private CourseRepository $courseRepository
    )
    {
    }

    public function findAll(CourseFilter $filterQuery, PageableData $pageableData): LengthAwarePaginator
    {
        return $this->courseRepository->findAll($filterQuery, $pageableData);
    }

    public function store(Course $courseToSave): Course
    {
        $courseToSave->owner_id = Auth::id();
        return $this->courseRepository->store($courseToSave);
    }

    public function update(Course $oldCourse, Course $newCourse): Course
    {
        return $this->courseRepository->update($oldCourse, $newCourse);
    }

    public function updatePartial(Course $oldCourse, Course $newCourse): Course
    {
        return $this->courseRepository->updatePartial($oldCourse, $newCourse);
    }

    public function restore(int $courseId): Course
    {
        return $this->courseRepository->restore($courseId);
    }

    public function deleteSoft(Course $course): void
    {
        $this->courseRepository->deleteSoft($course);
    }

    public function deleteHard(Course $course): void
    {
        $this->courseRepository->deleteHard($course);
    }
}
