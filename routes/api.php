<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalorieController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\XpHistoryController;
use Mews\Captcha\Captcha;
use Illuminate\Support\Facades\URL;
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

// Account routes
Route::get('/accounts', [AccountController::class, 'index']);
Route::post('/accounts', [AccountController::class, 'store']);
Route::put('/accounts/{id}', [AccountController::class, 'update']);
Route::delete('/accounts/{id}', [AccountController::class, 'destroy']);
Route::post('/accounts/login', [AccountController::class, 'login']);
Route::get('/accounts/verify/{id}', [AccountController::class, 'verify'])->name('account.verify');

// Custom route for verification view, expects 'account' in request input as JSON
Route::get('/captcha', function () {
    return response()->json([
        'captcha' => URL::to(captcha_src('flat')),
    ]);
});
// Calories routes
Route::get('/calories', [CalorieController::class, 'index']);
Route::post('/calories', [CalorieController::class, 'store']);
Route::get('/calories/{id}', [CalorieController::class, 'show']);
Route::put('/calories/{id}', [CalorieController::class, 'update']);
Route::delete('/calories/{id}', [CalorieController::class, 'destroy']);

// Custom AI calories prediction route
Route::post('/calories/check', [CalorieController::class, 'checkCalories']);

// Workouts routes
Route::get('/workouts', [WorkoutController::class, 'index']);
Route::post('/workouts', [WorkoutController::class, 'store']);
Route::get('/workouts/{id}', [WorkoutController::class, 'show']);
Route::put('/workouts/{id}', [WorkoutController::class, 'update']);
Route::delete('/workouts/{id}', [WorkoutController::class, 'destroy']);
// routes/api.php
Route::get('/xp-history', [XpHistoryController::class, 'index']);
Route::post('/xp-history', [XpHistoryController::class, 'store']);
Route::get('/xp-history/{id}', [XpHistoryController::class, 'show']);
Route::put('/xp-history/{id}', [XpHistoryController::class, 'update']);
Route::delete('/xp-history/{id}', [XpHistoryController::class, 'destroy']);

// routes/api.php
Route::get('/xp-history', [XpHistoryController::class, 'index']);
Route::post('/xp-history', [XpHistoryController::class, 'store']);
Route::get('/xp-history/{id}', [XpHistoryController::class, 'show']);
Route::put('/xp-history/{id}', [XpHistoryController::class, 'update']);
Route::delete('/xp-history/{id}', [XpHistoryController::class, 'destroy']);
