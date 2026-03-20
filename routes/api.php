<?php

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

Route::post('/register', [App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [App\Http\Controllers\Api\AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [App\Http\Controllers\Api\AuthController::class, 'me']);
    Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);

    Route::apiResource('patients', App\Http\Controllers\Api\PatientController::class);
    Route::apiResource('health-records', App\Http\Controllers\Api\HealthRecordController::class);
});
