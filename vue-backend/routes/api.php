<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\AttemptController;
use App\Http\Controllers\API\TaskCategoryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('user', [AuthController::class, 'user'])->middleware('auth:sanctum');



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});



Route::get('/tasks', [TaskController::class, 'index']);
Route::get('/tasks/{day}', [TaskController::class, 'getTasksForDay']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{id}', [TaskController::class, 'update']);
Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::get('task/{id}', [TaskController::class, 'showById']);

Route::get('/task-categories', [TaskCategoryController::class, 'index']);


Route::post('/tasks/{taskId}/record-attempt', [AttemptController::class, 'recordAttempt']);
