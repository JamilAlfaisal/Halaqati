<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\QuranClassController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Authentication routes
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });
});

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // Teacher routes
    Route::group(['prefix' => 'teacher'], function () {
        Route::get('dashboard', [TeacherController::class, 'dashboard']);
        Route::get('students', [TeacherController::class, 'students']);
        Route::get('students/{student}/progress', [TeacherController::class, 'studentProgress']);
        Route::get('classes', [TeacherController::class, 'classes']);
        Route::get('classes/{class}/progress', [TeacherController::class, 'classProgress']);
    });

    // Student routes
    Route::group(['prefix' => 'student'], function () {
        Route::get('dashboard', [StudentController::class, 'dashboard']);
        Route::get('assignments', [StudentController::class, 'assignments']);
        Route::post('assignments/{assignment}/complete', [StudentController::class, 'markAssignmentComplete']);
        Route::put('assignments/{assignment}/progress', [StudentController::class, 'updateAssignmentProgress']);
        Route::get('progress-history', [StudentController::class, 'progressHistory']);
    });

    // Assignment routes
    Route::apiResource('assignments', AssignmentController::class);
    Route::post('assignments/{assignment}/students/{student}/feedback', [AssignmentController::class, 'addTeacherFeedback']);

    // Class routes
    Route::apiResource('classes', QuranClassController::class, ['parameters' => ['classes' => 'quranClass']]);
    Route::post('classes/{quranClass}/students', [QuranClassController::class, 'addStudent']);
    Route::delete('classes/{quranClass}/students/{student}', [QuranClassController::class, 'removeStudent']);
    Route::get('classes/{quranClass}/weekly-progress', [QuranClassController::class, 'weeklyProgress']);

    // General resource routes (admin access)
    Route::apiResource('teachers', TeacherController::class)->except(['store', 'destroy']);
    Route::apiResource('students', StudentController::class)->except(['store', 'destroy']);
});
