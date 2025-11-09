<?php


use Modules\Auth\Http\Controllers\AuthController;
use Modules\Auth\Http\Middlewares\JwtAuthMiddleware;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'loginEmailPassword']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(JwtAuthMiddleware::class);
    Route::get('/me', [AuthController::class, 'me'])->middleware(JwtAuthMiddleware::class);
    /*
     * Route::post('/login/phone', [AuthController::class, 'loginPhone']);
     * Route::post('/login/telegram', [AuthController::class, 'loginTelegram']);
     * и тд
     */
});