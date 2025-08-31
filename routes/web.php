<?php

use Illuminate\Support\Facades\Route;
use App\Models\Account;
use App\Models\EmailVerification;
use App\Http\Controllers\CalorieController;


Route::get('/', function () {
    return view('403');
});
Route::get('verification-show', [AccountController::class, 'verificationShow'])->name('verification.show');