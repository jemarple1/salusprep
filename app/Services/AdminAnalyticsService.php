<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\GuestDevice;
use App\Models\Payment;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

class AdminAnalyticsService
{
    /** @return array<string, int|float|string> */
    public function summary(): array
    {
        $now = now();
        $completedPayments = Payment::query()->where('status', Payment::STATUS_COMPLETED);

        return [
            'total_users' => User::query()->count(),
            'signups_today' => User::query()->whereDate('created_at', $now->toDateString())->count(),
            'signups_7d' => User::query()->where('created_at', '>=', $now->copy()->subDays(7))->count(),
            'signups_30d' => User::query()->where('created_at', '>=', $now->copy()->subDays(30))->count(),
            'total_purchases' => (clone $completedPayments)->count(),
            'purchases_today' => (clone $completedPayments)->whereDate('paid_at', $now->toDateString())->count(),
            'purchases_7d' => (clone $completedPayments)->where('paid_at', '>=', $now->copy()->subDays(7))->count(),
            'total_revenue_cents' => (int) (clone $completedPayments)->sum('amount_cents'),
            'revenue_30d_cents' => (int) (clone $completedPayments)->where('paid_at', '>=', $now->copy()->subDays(30))->sum('amount_cents'),
            'unlocked_sections' => SectionAccess::query()->whereNotNull('unlocked_at')->count(),
            'completed_quizzes' => ExamSession::query()->where('status', ExamSession::STATUS_COMPLETED)->count(),
            'active_users_7d' => User::query()->where('last_login_at', '>=', $now->copy()->subDays(7))->count(),
            'total_guests' => GuestDevice::query()->count(),
            'active_guests_7d' => GuestDevice::query()->where('last_seen_at', '>=', $now->copy()->subDays(7))->count(),
            'guest_conversions' => GuestDevice::query()->whereNotNull('converted_user_id')->count(),
        ];
    }

    /** @return array<string, int|float|string> */
    public function guestSummary(): array
    {
        $now = now();

        return [
            'total_guests' => GuestDevice::query()->count(),
            'active_guests_7d' => GuestDevice::query()->where('last_seen_at', '>=', $now->copy()->subDays(7))->count(),
            'guests_30d' => GuestDevice::query()->where('first_seen_at', '>=', $now->copy()->subDays(30))->count(),
            'guest_questions_answered' => (int) ExamSession::query()
                ->whereNotNull('device_id')
                ->sum('questions_answered'),
            'guest_quizzes_completed' => ExamSession::query()
                ->whereNotNull('device_id')
                ->where('status', ExamSession::STATUS_COMPLETED)
                ->count(),
            'guest_conversions' => GuestDevice::query()->whereNotNull('converted_user_id')->count(),
            'guest_avg_active_seconds' => (int) round((float) GuestDevice::query()->avg('total_active_seconds')),
        ];
    }

    /** @return list<array{label: string, date: string, value: int}> */
    public function guestVisitChart(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = GuestDevice::query()
            ->selectRaw('DATE(first_seen_at) as day, COUNT(*) as total')
            ->where('first_seen_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        return $this->fillDailySeries($start, $days, $counts);
    }

    /** @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, GuestDevice> */
    public function guestsPaginated(?string $sort = null, string $direction = 'desc', int $perPage = 20)
    {
        $direction = strtolower($direction) === 'asc' ? 'asc' : 'desc';
        $sortKey = in_array($sort, self::GUEST_SORT_COLUMNS, true) ? $sort : 'last_seen';

        $query = GuestDevice::query()
            ->with([
                'convertedUser:id,name,email',
                'sectionProgress:device_id,certification_level,preview_started_at',
            ])
            ->withCount([
                'sectionProgress as platforms_count',
                'examSessions as quizzes_count',
                'examSessions as completed_quizzes_count' => fn ($query) => $query
                    ->where('status', ExamSession::STATUS_COMPLETED),
                'studySessions',
                'exerciseCompletions',
            ])
            ->withSum('examSessions as questions_answered', 'questions_answered');

        match ($sortKey) {
            'visitor' => $query->orderBy('display_name', $direction)->orderBy('device_id', $direction),
            'location' => $query->orderBy('country_name', $direction)->orderBy('country_code', $direction),
            'referral' => $query
                ->orderBy('utm_source', $direction)
                ->orderBy('referrer_host', $direction),
            'platforms' => $query->orderBy('platforms_count', $direction),
            'questions' => $query->orderBy('questions_answered', $direction),
            'quizzes' => $query
                ->orderBy('completed_quizzes_count', $direction)
                ->orderBy('quizzes_count', $direction),
            'study' => $query->orderBy('study_sessions_count', $direction),
            'skills' => $query->orderBy('exercise_completions_count', $direction),
            'time' => $query->orderBy('total_active_seconds', $direction),
            'first_seen' => $query->orderBy('first_seen_at', $direction),
            'status' => $query->orderBy('converted_user_id', $direction)->orderBy('converted_at', $direction),
            default => $query->orderBy('last_seen_at', $direction),
        };

        if ($sortKey !== 'last_seen') {
            $query->orderByDesc('last_seen_at');
        }

        return $query
            ->paginate($perPage, ['*'], 'guest_page')
            ->withQueryString();
    }

    private const GUEST_SORT_COLUMNS = [
        'visitor',
        'location',
        'referral',
        'platforms',
        'questions',
        'quizzes',
        'study',
        'skills',
        'time',
        'first_seen',
        'last_seen',
        'status',
    ];

    /** @return array{unique_pages: int, visits_7d: int, visits_30d: int, most_visited_path: ?string} */
    public function guestProfileSummary(GuestDevice $guest): array
    {
        $now = now();

        $mostVisited = $guest->pageVisits()
            ->selectRaw('path, COUNT(*) as total')
            ->groupBy('path')
            ->orderByDesc('total')
            ->first();

        return [
            'unique_pages' => (int) $guest->pageVisits()->distinct()->count('path'),
            'visits_7d' => $guest->pageVisits()->where('visited_at', '>=', $now->copy()->subDays(7))->count(),
            'visits_30d' => $guest->pageVisits()->where('visited_at', '>=', $now->copy()->subDays(30))->count(),
            'most_visited_path' => $mostVisited?->path,
            'most_visited_count' => $mostVisited ? (int) $mostVisited->total : 0,
        ];
    }

    /** @return list<array{id: string, lat: float, lon: float, label: string, country: ?string}> */
    public function guestGeoPoints(int $limit = 500): array
    {
        $geo = app(SignupGeoService::class);

        return GuestDevice::query()
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNotNull('latitude')
                        ->whereNotNull('longitude');
                })->orWhereNotNull('country_code');
            })
            ->latest('first_seen_at')
            ->limit($limit)
            ->get([
                'device_id',
                'country_code',
                'country_name',
                'latitude',
                'longitude',
                'first_ip',
            ])
            ->map(function (GuestDevice $device) use ($geo) {
                $point = $geo->mapPointForGuestDevice($device);

                if ($point === null) {
                    return null;
                }

                return [
                    'id' => $device->device_id,
                    'lat' => $point['lat'],
                    'lon' => $point['lon'],
                    'label' => 'Guest · '.($device->first_ip ?? substr($device->device_id, 0, 8)),
                    'country' => $point['country'],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /** @return list<array{label: string, date: string, value: int}> */
    public function signupChart(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = User::query()
            ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
            ->where('created_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        return $this->fillDailySeries($start, $days, $counts);
    }

    /** @return list<array{label: string, date: string, value: int}> */
    public function purchaseChart(int $days = 30): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $counts = Payment::query()
            ->selectRaw('DATE(paid_at) as day, COUNT(*) as total')
            ->where('status', Payment::STATUS_COMPLETED)
            ->whereNotNull('paid_at')
            ->where('paid_at', '>=', $start)
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day');

        return $this->fillDailySeries($start, $days, $counts);
    }

    /** @return Collection<int, User> */
    public function recentSignups(int $limit = 10): Collection
    {
        return User::query()
            ->latest()
            ->limit($limit)
            ->get(['id', 'name', 'email', 'created_at']);
    }

    /** @return Collection<int, User> */
    public function recentLogins(int $limit = 10): Collection
    {
        return User::query()
            ->whereNotNull('last_login_at')
            ->orderByDesc('last_login_at')
            ->limit($limit)
            ->get(['id', 'name', 'email', 'last_login_at', 'created_at']);
    }

    /** @return Collection<int, Payment> */
    public function recentPurchases(int $limit = 10): Collection
    {
        return Payment::query()
            ->with('user:id,name,email')
            ->where('status', Payment::STATUS_COMPLETED)
            ->orderByDesc('paid_at')
            ->limit($limit)
            ->get();
    }

    /** @return Collection<int, User> */
    public function marketingEmailSubscribers(): Collection
    {
        return User::query()
            ->where('marketing_emails_opt_in', true)
            ->orderBy('email')
            ->get(['id', 'name', 'email', 'created_at']);
    }

    public function marketingEmailsExport(): string
    {
        return $this->marketingEmailSubscribers()
            ->pluck('email')
            ->implode(', ');
    }

    /** @return \Illuminate\Contracts\Pagination\LengthAwarePaginator<int, User> */
    public function usersPaginated(int $perPage = 25)
    {
        return User::query()
            ->with(['sectionAccesses' => fn ($query) => $query->whereNotNull('unlocked_at')])
            ->withCount(['payments as purchases_count' => fn ($query) => $query->where('status', Payment::STATUS_COMPLETED)])
            ->withCount(['sectionAccesses as unlocked_sections_count' => fn ($query) => $query->whereNotNull('unlocked_at')])
            ->withCount('examSessions')
            ->latest()
            ->paginate($perPage);
    }

    /** @return list<array{label: string, value: int, color: string}> */
    public function platformQuizSlices(): array
    {
        $counts = ExamSession::query()
            ->selectRaw('certification_level, COUNT(*) as total')
            ->groupBy('certification_level')
            ->pluck('total', 'certification_level');

        return $this->platformSlices($counts);
    }

    /** @return list<array{label: string, value: int, color: string}> */
    public function platformPurchaseSlices(): array
    {
        $counts = Payment::query()
            ->where('status', Payment::STATUS_COMPLETED)
            ->selectRaw('certification_level, COUNT(*) as total')
            ->groupBy('certification_level')
            ->pluck('total', 'certification_level');

        return $this->platformSlices($counts);
    }

    /** @return list<array{id: int, lat: float, lon: float, label: string, country: ?string}> */
    public function signupGeoPoints(int $limit = 500): array
    {
        $geo = app(SignupGeoService::class);

        return User::query()
            ->where(function ($query) {
                $query->where(function ($inner) {
                    $inner->whereNotNull('signup_latitude')
                        ->whereNotNull('signup_longitude');
                })->orWhereNotNull('signup_country_code');
            })
            ->latest()
            ->limit($limit)
            ->get(['id', 'name', 'signup_country_code', 'signup_country_name', 'signup_latitude', 'signup_longitude'])
            ->map(function (User $user) use ($geo) {
                $point = $geo->mapPointForUser($user);

                if ($point === null) {
                    return null;
                }

                return [
                    'id' => $user->id,
                    'lat' => $point['lat'],
                    'lon' => $point['lon'],
                    'label' => $user->name,
                    'country' => $point['country'],
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<string, mixed>  $counts
     * @return list<array{label: string, date: string, value: int}>
     */
    private function fillDailySeries(Carbon $start, int $days, Collection $counts): array
    {
        $series = [];
        $period = CarbonPeriod::create($start->toDateString(), $start->copy()->addDays($days - 1)->toDateString());

        foreach ($period as $date) {
            $key = $date->toDateString();
            $series[] = [
                'date' => $key,
                'label' => $date->format('M j'),
                'value' => (int) ($counts[$key] ?? 0),
            ];
        }

        return $series;
    }

    /**
     * @param  Collection<string, mixed>  $counts
     * @return list<array{label: string, value: int, color: string}>
     */
    private function platformSlices(Collection $counts): array
    {
        $colors = [
            CertificationLevel::EMT_BASIC => '#4ade80',
            CertificationLevel::EMT_ADVANCED => '#3399cc',
            CertificationLevel::PARAMEDIC => '#fbbf24',
            CertificationLevel::NCLEX_PN => '#c084fc',
        ];

        $slices = [];

        foreach (CertificationLevel::all() as $level) {
            $slices[] = [
                'label' => CertificationLevel::label($level),
                'value' => (int) ($counts[$level] ?? 0),
                'color' => $colors[$level],
            ];
        }

        return $slices;
    }
}
