<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\AuthController;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


// Route::group(['middleware' => 'auth'], function() {
// });

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'ability:admin,user'])->group( function () {

    Route::controller(ProjectController::class)->group(function() {
        Route::get('/projects', 'index');
        Route::get('/projects/{id}', 'show');
        Route::post('/projects', 'save');
    });

    // tasks
    Route::controller(TaskController::class)->group(function() {
        Route::get('/projects/{project_id}/tasks', 'index');
        Route::get('/projects/{project_id}/tasks/{task_id}', 'show');
        Route::post('/projects/{project_id}/tasks/', 'save');
    });
});

Route::middleware(['auth:sanctum', 'ability:admin'])->group( function () {
    Route::controller(ProjectController::class)->group(function() {
        Route::put('/projects/{id}', 'edit');
        Route::delete('/projects/{id}', 'delete');
    });
    Route::controller(TaskController::class)->group(function() {
        Route::put('/projects/{project_id}/tasks/{task_id}', 'edit');
        Route::delete('/projects/{project_id}/tasks/{task_id}', 'delete');
    });
});
