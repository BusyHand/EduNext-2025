<?php

namespace Modules\Auth\Http\Controllers;

/**
 * @OA\Tag(
 *     name="AUTH",
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
