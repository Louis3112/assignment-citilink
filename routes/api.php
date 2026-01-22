<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['middleware' => 'api'], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->name('login');
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
    });

    Route::get('courses/{id}/students', [\App\Http\Controllers\CourseController::class, 'showStudents']);
    Route::apiResource('courses', CourseController::class);

    Route::post('courses/{id}/enroll', [\App\Http\Controllers\EnrollmentController::class, 'store']);
    Route::get('my-courses', [\App\Http\Controllers\EnrollmentController::class, 'index']);
});