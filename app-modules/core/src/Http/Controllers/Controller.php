<?php

namespace Modules\Core\Http\Controllers;

/**
 * @OA\Tag(
 *     name="Test",
 *     description="Тестовые методы"
 * )
 */
class Controller
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
    public function test()
    {
        return response()->json(['message' => 'Swagger работает!']);
    }
}
