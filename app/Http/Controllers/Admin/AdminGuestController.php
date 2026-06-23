<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestDevice;
use App\Services\AdminAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminGuestController extends Controller
{
    public function __construct(private AdminAnalyticsService $analytics) {}

    public function show(Request $request, GuestDevice $guest): View
    {
        $guest->load([
            'convertedUser:id,name,email',
            'sectionProgress:device_id,certification_level,preview_started_at',
            'activeStudyClubMembers',
        ]);

        $guest->loadCount([
            'pageVisits',
            'examSessions as quizzes_count',
            'examSessions as completed_quizzes_count' => fn ($query) => $query
                ->where('status', \App\Models\ExamSession::STATUS_COMPLETED),
            'studySessions',
            'exerciseCompletions',
        ]);

        $guest->loadSum('examSessions as questions_answered', 'questions_answered');

        $visits = $guest->pageVisits()
            ->latest('visited_at')
            ->paginate(50)
            ->withQueryString();

        $visitDays = $visits->getCollection()
            ->groupBy(fn ($visit) => $visit->visited_at->timezone(config('app.timezone'))->toDateString());

        return view('admin.guest-show', [
            'guest' => $guest,
            'visits' => $visits,
            'visitDays' => $visitDays,
            'profile' => $this->analytics->guestProfileSummary($guest),
        ]);
    }
}
