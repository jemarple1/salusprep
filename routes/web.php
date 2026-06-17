<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\StudyController;
use App\Http\Middleware\ResolveSection;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/emt-basic');

Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

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

        Route::middleware('auth')->group(function () {
            Route::get('/dashboard', DashboardController::class)->name('platform.dashboard');
            Route::get('/study', [StudyController::class, 'index'])->name('study.index');
            Route::post('/study/start', [StudyController::class, 'start'])->name('study.start');
            Route::get('/study/{studySession}', [StudyController::class, 'show'])->name('study.show');
            Route::post('/study/{studySession}/advance', [StudyController::class, 'advance'])->name('study.advance');
            Route::post('/exam/{session}/pay', [PaymentController::class, 'checkout'])->name('exam.pay');
            Route::post('/unlock', [PaymentController::class, 'checkoutSection'])->name('platform.unlock');
            Route::get('/exam/{session}/payment/success', [PaymentController::class, 'success'])->name('payment.success');
        });
    });
