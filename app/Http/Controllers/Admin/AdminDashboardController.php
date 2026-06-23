<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AdminAnalyticsService;
use App\Services\PreviewAccessService;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(private AdminAnalyticsService $analytics) {}

    public function index(Request $request): View
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
            'guestSummary' => $this->analytics->guestSummary(),
            'guestVisitChart' => $this->analytics->guestVisitChart(),
            'guestGeoPoints' => $this->analytics->guestGeoPoints(),
            'guests' => $this->analytics->guestsPaginated(
                $request->query('guest_sort'),
                (string) $request->query('guest_dir', 'desc'),
            ),
            'marketingSubscribers' => $this->analytics->marketingEmailSubscribers(),
            'marketingEmailsExport' => $this->analytics->marketingEmailsExport(),
            'studyClubMembers' => $this->analytics->studyClubMembers(),
            'studyClubEmailsExport' => $this->analytics->studyClubEmailsExport(),
            'certificationLevels' => CertificationLevel::labels(),
        ]);
    }
}
