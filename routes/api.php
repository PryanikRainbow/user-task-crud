<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('users')->group(function () {
    Route::post('/', [UserController::class, 'create']);
    Route::get('/', [UserController::class, 'index']);
    Route::get('{id}', [UserController::class, 'show']);
    Route::put('{id}', [UserController::class, 'update']);
    Route::delete('{id}', [UserController::class, 'remove']);
    
    Route::prefix('/{userId}/tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->middleware(['user.exists',]);
        Route::post('/', [TaskController::class, 'create'])->middleware(['user.exists',]);
        Route::get('/stats', [TaskController::class, 'userTaskStats']);
        Route::get('{taskId}', [TaskController::class, 'show'])->middleware(['user.exists',]);
        Route::put('{taskId}', [TaskController::class, 'update'])->middleware(['user.exists',]);
        Route::delete('{taskId}', [TaskController::class, 'remove'])->middleware(['user.exists',]);
        Route::delete('/', [TaskController::class, 'removeUnprocessed'])->middleware(['user.exists',]);
    });
});


Route::prefix('tasks')->group(function () {
    Route::get('stats', [TaskController::class, 'taskStats']);
});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
