<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\MockExamController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AccountSettingsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\PlatformPaywallController;
use App\Http\Controllers\PaywallFocusController;
use App\Http\Controllers\PlatformWelcomeController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StudyController;
use App\Http\Middleware\ResolveSection;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/emt-basic');

require __DIR__.'/seo.php';

Route::get('/terms', [LegalController::class, 'terms'])->name('legal.terms');
Route::get('/privacy', [LegalController::class, 'privacy'])->name('legal.privacy');
Route::get('/about', [LegalController::class, 'about'])->name('legal.about');

Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('auth.social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('auth.social.callback');

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
    Route::put('/settings/exam-date/{sectionSlug}', [AccountSettingsController::class, 'updateExamDate'])->name('settings.exam-date.update');
    Route::delete('/settings/account', [AccountSettingsController::class, 'destroy'])->name('settings.account.destroy');
});

Route::prefix('admin')->group(base_path('routes/admin.php'));

Route::prefix('{section}')
    ->middleware(ResolveSection::class)
    ->group(function () {
        Route::get('/', PlatformController::class)->name('platform.home');
        Route::get('/unlock', PlatformPaywallController::class)->name('platform.paywall');
        Route::post('/unlock/focus', PaywallFocusController::class)->name('platform.paywall.focus');

        Route::middleware('auth')->group(function () {
            Route::post('/unlock', [PaymentController::class, 'checkoutSection'])->name('platform.unlock');
            Route::post('/payment/intent', [PaymentController::class, 'createSectionPaymentIntent'])->name('platform.payment-intent');
            Route::get('/checkout', [PaymentController::class, 'startSectionCheckout'])->name('platform.checkout');
            Route::get('/welcome', [PlatformWelcomeController::class, 'show'])->name('platform.welcome');
            Route::post('/welcome/exam-date', [PlatformWelcomeController::class, 'updateExamDate'])->name('platform.welcome.exam-date');
            Route::get('/exam/{session}/payment/success', [PaymentController::class, 'success'])->name('payment.success');
            Route::post('/exam/{session}/pay', [PaymentController::class, 'checkout'])->name('exam.pay');
        });

        Route::get('/exam/{session}/paywall', [ExamController::class, 'paywall'])->name('exam.paywall');

        Route::middleware('preview')->group(function () {
            Route::post('/exam/preview-answer', [ExamController::class, 'answerPreview'])->name('exam.preview-answer');
            Route::post('/exam/start', [ExamController::class, 'start'])->name('exam.start');
            Route::get('/exam/{session}', [ExamController::class, 'show'])->name('exam.show');
            Route::post('/exam/{session}/questions/{question}', [ExamController::class, 'answer'])->name('exam.answer');
            Route::post('/exam/{session}/continue', [ExamController::class, 'continue'])->name('exam.continue');
            Route::get('/exam/{session}/results', [ExamController::class, 'results'])->name('exam.results');
            Route::post('/exam/{session}/finish', [ExamController::class, 'finish'])->name('exam.finish');

            Route::get('/study', [StudyController::class, 'index'])->name('study.index');
            Route::get('/study/deck', [StudyController::class, 'deck'])->name('study.deck');
            Route::post('/study/start', [StudyController::class, 'start'])->name('study.start');
            Route::get('/study/{studySession}', [StudyController::class, 'show'])->name('study.show');
            Route::post('/study/{studySession}/advance', [StudyController::class, 'advance'])->name('study.advance');

            Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
            Route::get('/review/{concept}', [ReviewController::class, 'show'])->name('review.show');

            Route::get('/skills', [ExerciseController::class, 'index'])->name('skills.index');
            Route::get('/dashboard', DashboardController::class)->name('platform.dashboard');

            Route::post('/mock-exam/start', [MockExamController::class, 'start'])->name('mock-exam.start');
            Route::get('/mock-exam/{session}/outcome', [MockExamController::class, 'outcome'])->name('mock-exam.outcome');
            Route::get('/mock-exam/{session}', [MockExamController::class, 'show'])->name('mock-exam.show');
            Route::post('/mock-exam/{session}/questions/{question}/answer', [MockExamController::class, 'answer'])->name('mock-exam.answer');

            Route::get('/exercises/{exercise}', [ExerciseController::class, 'show'])->name('exercises.show');
            Route::post('/exercises/{exercise}/check', [ExerciseController::class, 'check'])->name('exercises.check');
        });
    });
