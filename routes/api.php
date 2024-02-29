<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TasksController;
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

// Public Routes

Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class);
Route::get('/logout', LogoutController::class);

// Protected Routes

Route::middleware('auth:sanctum')->group(function () {

    Route::resource('/projects', ProjectsController::class);
    Route::resource('/tasks', TasksController::class);

    Route::post('/upload-profile-image', [FileController::class, 'uploadProfileImage']);
    Route::get('/get-profile-image/{userId}', [FileController::class, 'getProfileImage']);
    Route::delete('/delete-profile-image', [FileController::class, 'deleteProfileImage']);

    Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
    Route::patch('/update-profile', [UserController::class, 'updateProfile']);
});

Route::get('/user', function (Request $request) {
    return $request->user();

});
