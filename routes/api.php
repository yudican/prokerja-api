<?php

use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\V1\Auth\LoginController;
use App\Http\Controllers\V1\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
});

Route::prefix('jobs')->middleware('auth:sanctum')->group(function () {
    Route::get('all', [JobVacancyController::class, 'listJobs']);
    Route::get('detail/{job_id}', [JobVacancyController::class, 'getJobDetail']);
    Route::post('apply', [JobVacancyController::class, 'apply']);
    Route::post('apply/test', [JobVacancyController::class, 'applyTest']);
});

Route::prefix('profile')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [JobVacancyController::class, 'getUserProfile']);
    Route::post('update', [JobVacancyController::class, 'updateProfile']);
    Route::post('update/password', [JobVacancyController::class, 'updatePassword']);
});
