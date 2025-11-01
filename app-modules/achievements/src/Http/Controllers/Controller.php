<?php

namespace Modules\Achievements\Http\Controllers;

/**
 * @OA\Tag(
 *     name="Achivki",
 *     description="Тестовые методы"
 * )
 */
class Controller
{
    public function test()
    {
        return response()->json(['message' => 'Swagger работает!']);
    }
}
