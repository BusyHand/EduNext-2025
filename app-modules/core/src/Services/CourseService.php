<?php

namespace Modules\Core\Services;

use App\Http\Requests\Pageable;
use App\Http\Requests\PageableData;
use Illuminate\Database\Eloquent\Collection;
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

    public function findAll(CourseFilter $filterQuery, PageableData $pageableData): Collection
    {
        return $this->courseRepository->findAll($filterQuery, $pageableData);
    }

    public function findById(Course $course)
    {

    }

    public function store()
    {

    }

    public function update()
    {

    }

    public function updatePartial()
    {

    }

    public function restore(string $courseId)
    {

    }

    public function deleteSoft()
    {

    }

    public function deleteHard()
    {

    }
}
