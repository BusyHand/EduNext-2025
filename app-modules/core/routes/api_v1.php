<?php

use Modules\Core\Http\Controllers\CourseController;
use Modules\Core\Http\Controllers\LessonController;
use Modules\Core\Http\Controllers\UserCourseController;
use Modules\Core\Http\Controllers\UserLessonController;

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

    //todo реализовать
    Route::post('/{lesson}/ask', [LessonController::class, 'askQuestion']);

    //todo реализовать доп 2
    Route::get('/{lesson}/generate-task', [LessonController::class, 'askQuestion']);

});

Route::prefix('/users')->group(function () {
    Route::get('/courses', [UserCourseController::class, 'findAll']);
    Route::post('/{user}/courses/{course}', [UserCourseController::class, 'store']);
    Route::patch('/{user}/courses/{course}/restore', [UserCourseController::class, 'restore']);
    Route::delete('/{user}/courses/{course}/soft', [UserCourseController::class, 'deleteSoft']);
    Route::delete('/{user}/courses/{course}/force', [UserCourseController::class, 'deleteHard']);
});

Route::prefix('/users')->group(function () {
    Route::get('/lessons', [UserLessonController::class, 'findAll']);
    Route::post('/{user}/lessons/{lessons}', [UserLessonController::class, 'store']);
    Route::patch('/{user}/lessons/{lessons}/restore', [UserLessonController::class, 'restore']);
    Route::delete('/{user}/lessons/{lessons}/soft', [UserLessonController::class, 'deleteSoft']);
    Route::delete('/{user}/lessons/{lessons}/force', [UserLessonController::class, 'deleteHard']);

    //todo реализовать
    Route::post('/{user}/lessons/{lessons}/action/complete', [UserLessonController::class, 'askQuestion']);
});
