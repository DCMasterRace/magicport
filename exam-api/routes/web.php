<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TaskController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('login', function () {
    return view('auth.login');
});

Route::middleware(['auth:sanctum', 'ability:admin,user'])->group( function () {
    Route::group(['prefix' => 'projects', 'as' => 'projects'], function() {
        Route::get('/', function() {
            return view('projects.index');
        });
    });
});
