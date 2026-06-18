<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\StudyController;
use App\Http\Middleware\ResolveSection;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/emt-basic');

Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/about', [LegalController::class, 'about'])->name('legal.about');

Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/forgot-password', [PasswordResetController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetController::class, 'store'])->middleware('throttle:6,1')->name('password.email');
    Route::get('/reset-password/{token}', [PasswordResetController::class, 'edit'])->name('password.reset');
    Route::post('/reset-password', [PasswordResetController::class, 'update'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/settings', [AccountSettingsController::class, 'edit'])->name('settings.edit');
    Route::put('/settings/profile', [AccountSettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::put('/settings/password', [AccountSettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::delete('/settings/account', [AccountSettingsController::class, 'destroy'])->name('settings.account.destroy');
});

Route::prefix('admin')->group(base_path('routes/admin.php'));

Route::prefix('{section}')
    ->middleware(ResolveSection::class)
    ->group(function () {
        Route::get('/', PlatformController::class)->name('platform.home');

        Route::post('/exam/start', [ExamController::class, 'start'])->name('exam.start');
        Route::get('/exam/{session}', [ExamController::class, 'show'])->name('exam.show');
        Route::post('/exam/{session}/questions/{question}', [ExamController::class, 'answer'])->name('exam.answer');
        Route::get('/exam/{session}/paywall', [ExamController::class, 'paywall'])->name('exam.paywall');
        Route::get('/exam/{session}/results', [ExamController::class, 'results'])->name('exam.results');
        Route::post('/exam/{session}/finish', [ExamController::class, 'finish'])->name('exam.finish');

        Route::get('/study', [StudyController::class, 'index'])->name('study.index');

        Route::middleware('auth')->group(function () {
            Route::get('/dashboard', DashboardController::class)->name('platform.dashboard');
            Route::post('/study/start', [StudyController::class, 'start'])->name('study.start');
            Route::get('/study/{studySession}', [StudyController::class, 'show'])->name('study.show');
            Route::post('/study/{studySession}/advance', [StudyController::class, 'advance'])->name('study.advance');
            Route::post('/exam/{session}/pay', [PaymentController::class, 'checkout'])->name('exam.pay');
            Route::post('/unlock', [PaymentController::class, 'checkoutSection'])->name('platform.unlock');
            Route::get('/exam/{session}/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        });
    });
