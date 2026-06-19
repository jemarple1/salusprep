<?php

namespace App\Services;

use App\Models\ExamSession;
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
        ];
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
