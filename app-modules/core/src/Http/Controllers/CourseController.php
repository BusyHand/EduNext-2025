<?php

namespace Modules\Core\Http\Controllers;

use App\Http\Dtos\PagebleDto;
use Illuminate\Http\Response;
use Modules\Core\Http\Filters\Requests\CourseFilterRequest;
use Modules\Core\Http\Mappers\CourseMapper;
use Modules\Core\Http\Requests\CourseStoreRequest;
use Modules\Core\Http\Requests\CourseUpdateRequest;
use Modules\Core\Http\Response\CourseDto;
use Modules\Core\Models\Course;
use Modules\Core\Services\CourseService;


readonly class CourseController
{
    public function __construct(
        private CourseService $courseService,
        private CourseMapper  $courseMapper
    )
    {
    }

    public function findAll(CourseFilterRequest $filterRequest): PagebleDto
    {
        $filterQuery = $this->courseMapper->toFilter($filterRequest);
        $paginateCourses = $this->courseService->findAll($filterQuery, $filterRequest->toPageableData());
        return $this->courseMapper->toPaginateSlimDtos($paginateCourses);
    }

    public function findById(Course $course): CourseDto
    {
        return $this->courseMapper->toDto($course);
    }

    public function store(CourseStoreRequest $courseData): CourseDto
    {
        $courseToSave = $this->courseMapper->toModelFromStore($courseData);
        $savedCourse = $this->courseService->store($courseToSave);
        return $this->courseMapper->toDto($savedCourse);
    }

    public function updatePartial(Course $course, CourseUpdateRequest $courseData): CourseDto
    {
        $newCourse = $this->courseMapper->toModelFromUpdate($courseData);
        $updatedCourse = $this->courseService->updatePartial($course, $newCourse);
        return $this->courseMapper->toDto($updatedCourse);
    }

    public function restore(int $courseId): CourseDto
    {
        $restoredCourse = $this->courseService->restore($courseId);
        return $this->courseMapper->toDto($restoredCourse);
    }

    public function deleteSoft(Course $course): Response
    {
        $this->courseService->deleteSoft($course);
        return response()->noContent();
    }

    public function deleteHard(Course $course): Response
    {
        $this->courseService->deleteHard($course);
        return response()->noContent();
    }
}
