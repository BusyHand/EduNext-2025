<?php

use Modules\Auth\Http\Middlewares\JwtAuthMiddleware;
use Modules\Core\Http\Controllers\CourseController;
use Modules\Core\Http\Controllers\LessonController;
use Modules\Core\Http\Controllers\TaskController;
use Modules\Core\Http\Controllers\UserCourseController;
use Modules\Core\Http\Controllers\UserLessonController;

Route::middleware(JwtAuthMiddleware::class)->group(function () {
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'findAll']);
        Route::get('/{course}', [CourseController::class, 'findById']);
        Route::post('/', [CourseController::class, 'store']);
        Route::patch('/{course}', [CourseController::class, 'updatePartial']);
        Route::patch('/{courseId}/restore', [CourseController::class, 'restore'])->whereNumber('courseId');
        Route::delete('/{course}/soft', [CourseController::class, 'deleteSoft']);
        Route::delete('/{course}/force', [CourseController::class, 'deleteHard']);
    });

    Route::prefix('lessons')->group(function () {
        Route::get('/', [LessonController::class, 'findAll']);
        Route::get('/{lesson}', [LessonController::class, 'findById']);
        Route::post('/', [LessonController::class, 'store']);
        Route::patch('/{lesson}', [LessonController::class, 'updatePartial']);
        Route::patch('/{lessonId}/restore', [LessonController::class, 'restore']);
        Route::delete('/{lesson}/soft', [LessonController::class, 'deleteSoft']);
        Route::delete('/{lesson}/force', [LessonController::class, 'deleteHard']);

        Route::post('/{lesson}/ask', [LessonController::class, 'askQuestion']);
        Route::get('/{lesson}/generate-task', [TaskController::class, 'generateTask']);
        Route::post('/{task}/answer', [TaskController::class, 'answerTask']);
    });

    Route::prefix('/users')->group(function () {
        Route::get('/courses', [UserCourseController::class, 'findAll']);
        Route::post('/{user}/courses/{course}/', [UserCourseController::class, 'store']);
        Route::patch('/{userId}/courses/{courseId}/restore', [UserCourseController::class, 'restore'])->whereNumber(['userId', 'courseId']);
        Route::delete('/{user}/courses/{course}/soft', [UserCourseController::class, 'deleteSoft']);
        Route::delete('/{user}/courses/{course}/force', [UserCourseController::class, 'deleteHard']);
    });

    Route::prefix('/users')->group(callback: function () {
        Route::get('/lessons', [UserLessonController::class, 'findAll']);
        Route::post('/{user}/lessons/{lesson}', [UserLessonController::class, 'store']);
        Route::patch('/{userId}/lessons/{lessonId}/restore', [UserLessonController::class, 'restore'])->whereNumber(['userId', 'lessonId']);
        Route::delete('/{user}/lessons/{lesson}/soft', [UserLessonController::class, 'deleteSoft']);
        Route::delete('/{user}/lessons/{lesson}/force', [UserLessonController::class, 'deleteHard']);

        Route::post('/{lesson}/complete', [UserLessonController::class, 'complete']);
    });


    Route::prefix('/tasks')->group(callback: function () {
        Route::get('/', [TaskController::class, 'findAll']);
        Route::post('/{task}/answer', [TaskController::class, 'answerTask']);
    });
});