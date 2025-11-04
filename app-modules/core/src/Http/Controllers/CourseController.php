<?php

namespace Modules\Core\Http\Controllers;

use Illuminate\Support\Collection;
use Modules\Core\Http\Dtos\CourseDto;
use Modules\Core\Http\Filters\Requests\CourseFilterRequest;
use Modules\Core\Http\Mappers\CourseMapper;
use Modules\Core\Models\Course;
use Modules\Core\Services\CourseService;

/**
 * @OA\Tag(
 *     name="Test",
 *     description="Тестовые методы"
 * )
 */
readonly class CourseController
{
    public function __construct(
        private CourseService $courseService,
        private CourseMapper  $courseMapper
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/courses",
     *     tags={"Test"},
     *     summary="Получить все курсы",
     *     @OA\Request (
     *
     *     )
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ"
     *     )
     * )
     */
    public function findAll(CourseFilterRequest $filterRequest): Collection
    {
        $filterQuery = $this->courseMapper->toFilter($filterRequest);
        $courses = $this->courseService->findAll($filterQuery, $filterRequest->getPaginateData());
        return $this->courseMapper->toSlimDtos($courses);
    }

    public function findById(Course $course)
    {

    }

    public function store(CourseDto $courseData)
    {

    }

    public function update(Course $course, CourseDto $courseData)
    {

    }

    public function updatePartial(Course $course, CourseDto $courseData)
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
