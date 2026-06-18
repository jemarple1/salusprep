<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdminAnalyticsService;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(private AdminAnalyticsService $analytics) {}

    public function index(): View
    {
        return view('admin.dashboard', [
            'summary' => $this->analytics->summary(),
            'signupChart' => $this->analytics->signupChart(),
            'purchaseChart' => $this->analytics->purchaseChart(),
            'recentSignups' => $this->analytics->recentSignups(),
            'recentLogins' => $this->analytics->recentLogins(),
            'recentPurchases' => $this->analytics->recentPurchases(),
            'users' => $this->analytics->usersPaginated(),
        ]);
    }
}
