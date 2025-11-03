<?php

namespace Modules\Auth\Http\Controllers;

use Modules\Core\Models\Course;

/**
 * @OA\Tag(
 *     name="Test",
 *     description="Тестовые методы"
 * )
 */
class PermissionController
{
    /**
     * @OA\Get(
     *     path="/api/test",
     *     tags={"Test"},
     *     summary="Проверка Swagger",
     *     @OA\Response(
     *         response=200,
     *         description="Успешный ответ"
     *     )
     * )
     */
    public function findAll()
    {

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
