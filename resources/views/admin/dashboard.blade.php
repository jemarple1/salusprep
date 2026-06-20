@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
    @php
        $formatMoney = fn (int $cents) => '$'.number_format($cents / 100, 2);
    @endphp

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Growth dashboard</h1>
        <p class="mt-2 text-sm text-slate-400">Signups, revenue, activity, and user list — last updated {{ now()->format('M j, Y g:i A T') }}.</p>
    </div>

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-medic/40 bg-medic/10 px-4 py-3 text-sm font-medium text-medic-light">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80 p-6">
        <h2 class="text-lg font-bold text-white">Preview access limit</h2>
        <p class="mt-1 text-sm text-slate-400">How many minutes of free preview each visitor gets from their first visit before the paywall.</p>

        <form method="POST" action="{{ route('admin.settings.preview-limit') }}" class="mt-5 flex flex-wrap items-end gap-4">
            @csrf
            <div>
                <label for="preview_minutes_limit" class="mb-1 block text-sm font-medium text-slate-300">Preview minutes</label>
                <input
                    type="number"
                    name="preview_minutes_limit"
                    id="preview_minutes_limit"
                    min="1"
                    max="1440"
                    value="{{ old('preview_minutes_limit', $previewMinutesLimit) }}"
                    class="w-32 rounded-lg border border-white/10 bg-navy px-3 py-2 text-white"
                    required
                >
            </div>
            <button type="submit" class="rounded-lg bg-medic px-5 py-2.5 font-bold text-white hover:bg-medic-dark">Save</button>
        </form>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-white">{{ number_format($summary['total_users']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Total users</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-medic-light">{{ number_format($summary['signups_30d']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Signups (30d) · {{ number_format($summary['signups_today']) }} today</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-ems-light">{{ number_format($summary['total_purchases']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Purchases · {{ number_format($summary['purchases_7d']) }} this week</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-3xl font-bold text-safety-light">{{ $formatMoney($summary['total_revenue_cents']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Total revenue · {{ $formatMoney($summary['revenue_30d_cents']) }} (30d)</p>
        </div>
    </div>

    <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-2xl font-bold text-white">{{ number_format($summary['signups_7d']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Signups (7d)</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-2xl font-bold text-white">{{ number_format($summary['active_users_7d']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Active logins (7d)</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-2xl font-bold text-white">{{ number_format($summary['unlocked_sections']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Section unlocks</p>
        </div>
        <div class="rounded-xl border border-white/10 bg-navy-light/80 p-5">
            <p class="text-2xl font-bold text-white">{{ number_format($summary['completed_quizzes']) }}</p>
            <p class="mt-1 text-sm text-slate-400">Completed quizzes</p>
        </div>
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <x-admin.line-chart title="Daily signups (30 days)" :points="$signupChart" value-label="signups" />
        <x-admin.line-chart title="Daily purchases (30 days)" :points="$purchaseChart" value-label="purchases" stroke="#3399cc" fill="rgba(51, 153, 204, 0.12)" />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-2">
        <x-admin.pie-chart title="Platform popularity — quiz sessions" :slices="$platformQuizSlices" />
        <x-admin.pie-chart title="Platform popularity — purchases" :slices="$platformPurchaseSlices" />
    </div>

    <div class="mb-8">
        <x-admin.signup-map
            title="Signup geography"
            :points="$signupGeoPoints"
            :total-signups="$summary['total_users']"
        />
    </div>

    <div class="mb-8 grid gap-6 lg:grid-cols-3">
        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent signups</h2>
            <ul class="mt-4 space-y-3">
                @forelse ($recentSignups as $user)
                    <li class="border-b border-white/5 pb-3 last:border-0 last:pb-0">
                        <p class="font-semibold text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $user->created_at->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">No users yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recently logged in</h2>
            <ul class="mt-4 space-y-3">
                @forelse ($recentLogins as $user)
                    <li class="border-b border-white/5 pb-3 last:border-0 last:pb-0">
                        <p class="font-semibold text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                        <p class="mt-1 text-xs text-medic-light">Last login {{ $user->last_login_at?->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">No login activity recorded yet.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-white/10 bg-navy-light/80 p-5">
            <h2 class="text-sm font-bold uppercase tracking-wider text-slate-400">Recent purchases</h2>
            <ul class="mt-4 space-y-3">
                @forelse ($recentPurchases as $payment)
                    <li class="border-b border-white/5 pb-3 last:border-0 last:pb-0">
                        <p class="font-semibold text-white">{{ $payment->user?->name ?? 'Unknown user' }}</p>
                        <p class="text-xs text-slate-400">{{ $payment->sectionLabel() }} · {{ $payment->formattedAmount() }}</p>
                        <p class="mt-1 text-xs text-slate-500">{{ $payment->paid_at?->diffForHumans() }}</p>
                    </li>
                @empty
                    <li class="text-sm text-slate-500">No purchases yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80 p-5 sm:p-6">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">All users</h2>
                <p class="mt-1 text-sm text-slate-400">Grant full platform access: choose a section in the <span class="font-semibold text-medic-light">Unlock section</span> column and click the green button.</p>
            </div>
            <p class="text-sm text-slate-400">{{ number_format($users->total()) }} total</p>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-[56rem] w-full text-left text-sm">
                <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-3 py-3 font-semibold">Name</th>
                        <th class="px-3 py-3 font-semibold">Email</th>
                        <th class="px-3 py-3 font-semibold">Signed up</th>
                        <th class="px-3 py-3 font-semibold">Last login</th>
                        <th class="px-3 py-3 font-semibold">Quizzes</th>
                        <th class="px-3 py-3 font-semibold">Purchases</th>
                        <th class="min-w-[14rem] px-3 py-3 font-semibold text-medic-light">Unlock section</th>
                        <th class="sticky right-0 bg-navy-light/95 px-3 py-3 font-semibold text-right backdrop-blur">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach ($users as $user)
                        <tr class="text-slate-300">
                            <td class="px-3 py-3 font-medium text-white">{{ $user->name }}</td>
                            <td class="px-3 py-3">{{ $user->email }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $user->created_at->format('M j, Y') }}</td>
                            <td class="px-3 py-3 whitespace-nowrap">{{ $user->last_login_at?->format('M j, Y g:i A') ?? '—' }}</td>
                            <td class="px-3 py-3">{{ number_format($user->exam_sessions_count) }}</td>
                            <td class="px-3 py-3">{{ number_format($user->purchases_count) }}</td>
                            <td class="min-w-[14rem] px-3 py-3">
                                @if ($user->sectionAccesses->isNotEmpty())
                                    <div class="mb-2 flex flex-wrap gap-1">
                                        @foreach ($user->sectionAccesses as $access)
                                            <span class="rounded-full bg-medic/20 px-2 py-0.5 text-xs font-medium text-medic-light">
                                                {{ $certificationLevels[$access->certification_level] ?? $access->certification_level }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('admin.users.unlock', $user) }}" class="flex flex-wrap items-center gap-2">
                                    @csrf
                                    <label class="sr-only" for="unlock-{{ $user->id }}">Platform for {{ $user->name }}</label>
                                    <select
                                        name="certification_level"
                                        id="unlock-{{ $user->id }}"
                                        class="min-w-[8.5rem] rounded-lg border border-white/10 bg-navy px-2 py-2 text-xs text-white"
                                        required
                                    >
                                        @foreach ($certificationLevels as $level => $label)
                                            <option value="{{ $level }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-lg bg-medic px-4 py-2 text-xs font-bold text-white hover:bg-medic-dark">
                                        Unlock section
                                    </button>
                                </form>
                            </td>
                            <td class="sticky right-0 bg-navy-light/95 px-3 py-3 text-right backdrop-blur">
                                <form
                                    method="POST"
                                    action="{{ route('admin.users.destroy', $user) }}"
                                    class="inline"
                                    onsubmit="return confirm('Delete {{ $user->email }}? This permanently removes their account, purchases, section unlocks, quiz history, and progress.')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="rounded-lg border border-rescue/40 px-3 py-1.5 text-xs font-semibold text-red-200 hover:border-rescue hover:bg-rescue/10">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->links() }}
        </div>
    </div>

    <div class="mb-8 rounded-2xl border border-white/10 bg-navy-light/80 p-5 sm:p-6">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h2 class="text-lg font-bold text-white">Marketing email subscribers</h2>
                <p class="mt-1 text-sm text-slate-400">Users who opted in to resources and emails at signup.</p>
            </div>
            <p class="text-sm font-semibold text-safety-light">{{ number_format($marketingSubscribers->count()) }} subscribed</p>
        </div>

        @if ($marketingSubscribers->isNotEmpty())
            <div class="mb-6 overflow-x-auto">
                <table class="min-w-full text-left text-sm">
                    <thead class="border-b border-white/10 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-3 py-3 font-semibold">Name</th>
                            <th class="px-3 py-3 font-semibold">Email</th>
                            <th class="px-3 py-3 font-semibold">Signed up</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach ($marketingSubscribers as $subscriber)
                            <tr class="text-slate-300">
                                <td class="px-3 py-3 font-medium text-white">{{ $subscriber->name }}</td>
                                <td class="px-3 py-3">{{ $subscriber->email }}</td>
                                <td class="px-3 py-3 whitespace-nowrap">{{ $subscriber->created_at->format('M j, Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div>
                <div class="mb-2 flex items-center justify-between gap-4">
                    <label for="marketing-emails-export" class="text-sm font-semibold text-slate-300">Export (comma-separated)</label>
                    <button type="button" id="copy-marketing-emails"
                        class="rounded-lg border border-white/10 px-3 py-1.5 text-xs font-semibold text-safety-light hover:border-safety/40 hover:text-safety">
                        Copy to clipboard
                    </button>
                </div>
                <textarea id="marketing-emails-export" readonly rows="4"
                    class="w-full rounded-xl border border-white/10 bg-navy px-4 py-3 text-sm text-slate-300 outline-none focus:ring-2 focus:ring-safety">{{ $marketingEmailsExport }}</textarea>
            </div>
        @else
            <p class="text-sm text-slate-500">No marketing subscribers yet.</p>
        @endif
    </div>

    @if ($marketingSubscribers->isNotEmpty())
        @push('scripts')
            <script>
                document.getElementById('copy-marketing-emails')?.addEventListener('click', async () => {
                    const textarea = document.getElementById('marketing-emails-export');
                    if (!textarea) return;

                    try {
                        await navigator.clipboard.writeText(textarea.value);
                    } catch {
                        textarea.select();
                        document.execCommand('copy');
                    }

                    const button = document.getElementById('copy-marketing-emails');
                    const original = button.textContent;
                    button.textContent = 'Copied!';
                    setTimeout(() => { button.textContent = original; }, 2000);
                });
            </script>
        @endpush
    @endif
@endsection
