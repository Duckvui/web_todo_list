<?php

use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\DangKyController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Middleware\EnsureAccountSession;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function (): void {
    Route::post('/dang-ky', [DangKyController::class, 'store'])->middleware('throttle:registration');
    Route::post('/dang-nhap', [SessionController::class, 'store'])->middleware('throttle:account-login');
    Route::post('/quen-mat-khau', [ChallengeController::class, 'forgot'])->middleware('throttle:otp-send');
    Route::post('/dat-lai-mat-khau', [ChallengeController::class, 'reset'])->middleware('throttle:otp-check');

    Route::middleware(['auth:web', EnsureAccountSession::class])->group(function (): void {
        Route::get('/me', [SessionController::class, 'show']);
        Route::get('/user', [SessionController::class, 'show']);
        Route::post('/dang-xuat', [SessionController::class, 'destroy']);
        Route::patch('/thong-tin-ca-nhan', [AccountController::class, 'update']);
        Route::put('/doi-mat-khau', [AccountController::class, 'password'])->middleware('throttle:account-login');
        Route::post('/xac-minh/gui-ma', [ChallengeController::class, 'sendVerification'])->middleware('throttle:otp-send');
        Route::post('/xac-minh/kiem-tra', [ChallengeController::class, 'verify'])->middleware('throttle:otp-check');
    });
});
