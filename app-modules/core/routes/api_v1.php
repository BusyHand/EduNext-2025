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
    Route::put('/{lesson}', [LessonController::class, 'update']);
    Route::patch('/{lesson}', [LessonController::class, 'updatePartial']);
    Route::patch('/restore/{lessonId}', [LessonController::class, 'restore']);
    Route::delete('/{lesson}/soft', [LessonController::class, 'deleteSoft']);
    Route::delete('/{lesson}/force', [LessonController::class, 'deleteHard']);

    //todo реализовать
    Route::post('/{lesson}/ask', [LessonController::class, 'askQuestion']);

    //todo реализовать
    Route::get('/{lesson}/generate-task', [LessonController::class, 'askQuestion']);

});

Route::prefix('/users/courses')->group(function () {
    Route::get('/', [UserCourseController::class, 'findAll']);
    Route::get('/{userCourse}', [UserCourseController::class, 'findById']);
    Route::post('/', [UserCourseController::class, 'store']);
    Route::put('/{userCourse}', [UserCourseController::class, 'update']);
    Route::patch('/{userCourse}', [UserCourseController::class, 'updatePartial']);
    Route::patch('/restore/{userCourseId}', [UserCourseController::class, 'restore']);
    Route::delete('/{userCourse}/soft', [UserCourseController::class, 'deleteSoft']);
    Route::delete('/{userCourse}/force', [UserCourseController::class, 'deleteHard']);

});

Route::prefix('/users/lessons')->group(function () {
    Route::get('/', [UserLessonController::class, 'findAll']);
    Route::get('/{userLesson}', [UserLessonController::class, 'findById']);
    Route::post('/', [UserLessonController::class, 'store']);
    Route::put('/{userLesson}', [UserLessonController::class, 'update']);
    Route::patch('/{userLesson}', [UserLessonController::class, 'updatePartial']);
    Route::patch('/restore/{userLessonId}', [UserLessonController::class, 'restore']);
    Route::delete('/{userLesson}/soft', [UserLessonController::class, 'deleteSoft']);
    Route::delete('/{userLesson}/force', [UserLessonController::class, 'deleteHard']);

    //todo реализовать
    Route::post('/{userLesson}/action/complete', [LessonController::class, 'askQuestion']);

});
