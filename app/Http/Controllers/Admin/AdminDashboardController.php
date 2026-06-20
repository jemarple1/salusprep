<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminAnalyticsService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(private AdminAnalyticsService $analytics) {}

    public function index(): View
    {
        return view('admin.dashboard', [
            'summary' => $this->analytics->summary(),
            'previewMinutesLimit' => Setting::getInt(PreviewAccessService::MINUTES_KEY, PreviewAccessService::DEFAULT_MINUTES),
            'signupChart' => $this->analytics->signupChart(),
            'purchaseChart' => $this->analytics->purchaseChart(),
            'platformQuizSlices' => $this->analytics->platformQuizSlices(),
            'platformPurchaseSlices' => $this->analytics->platformPurchaseSlices(),
            'signupGeoPoints' => $this->analytics->signupGeoPoints(),
            'recentSignups' => $this->analytics->recentSignups(),
            'recentLogins' => $this->analytics->recentLogins(),
            'recentPurchases' => $this->analytics->recentPurchases(),
            'users' => $this->analytics->usersPaginated(),
            'marketingSubscribers' => $this->analytics->marketingEmailSubscribers(),
            'marketingEmailsExport' => $this->analytics->marketingEmailsExport(),
            'certificationLevels' => CertificationLevel::labels(),
        ]);
    }
}
