<?php

namespace Modules\Core\Repositories;

use App\Http\Requests\Pageable;
use App\Http\Requests\PageableData;
use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Http\Filters\CourseFilter;
use Modules\Core\Models\Course;

class CourseRepository
{
    use Pageable;

    public function findAll(CourseFilter $filterQuery, PageableData $pageableData): Collection
    {
        $query = $filterQuery->apply(Course::query());
        return $this->applyPagination($query, $pageableData)
            ->get();
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
