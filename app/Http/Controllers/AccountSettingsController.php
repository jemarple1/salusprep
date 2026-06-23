<?php

namespace App\Http\Controllers;

use App\Services\ExamCountdownService;
use App\Services\SectionExamDateService;
use App\Support\CertificationLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountSettingsController extends Controller
{
    public function __construct(
        private ExamCountdownService $countdown,
        private SectionExamDateService $examDates,
    ) {}

    public function edit(Request $request): View
    {
        $user = $request->user();

        $examDateSections = $user->sectionAccesses()
            ->whereNotNull('unlocked_at')
            ->orderBy('certification_level')
            ->get()
            ->map(function ($access) {
                $level = $access->certification_level;

                return [
                    'access' => $access,
                    'slug' => CertificationLevel::slug($level),
                    'label' => CertificationLevel::labels()[$level] ?? $level,
                    'examCountdown' => $this->countdown->forDate($access->exam_date),
                ];
            });

        return view('settings.edit', [
            'user' => $user,
            'examDateSections' => $examDateSections,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['required', 'current_password'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('success', 'Password updated.');
    }

    public function updateExamDate(Request $request, string $sectionSlug): RedirectResponse
    {
        $level = CertificationLevel::fromSlug($sectionSlug);

        abort_unless($level !== null, 404);

        $user = $request->user();

        abort_unless($user->hasSectionAccess($level), 403);

        $access = $user->sectionAccessFor($level);

        abort_unless($access !== null, 404);

        $this->examDates->update($access, $request->input('exam_date'));

        $label = CertificationLevel::labels()[$level] ?? 'section';

        if ($request->input('exam_date') === '' || $request->input('exam_date') === null) {
            return redirect()
                ->route('settings.edit')
                ->with('success', "{$label} exam date cleared.");
        }

        return redirect()
            ->route('settings.edit')
            ->with('success', "{$label} exam date saved.");
    }

    public function updateDailyStudyEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'daily_study_email_opt_in' => ['sometimes', 'boolean'],
        ]);

        $request->user()->update([
            'daily_study_email_opt_in' => $request->boolean('daily_study_email_opt_in'),
        ]);

        return back()->with(
            'success',
            $request->boolean('daily_study_email_opt_in')
                ? 'Daily study emails turned on.'
                : 'Daily study emails turned off.',
        );
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('platform.home', 'emt-basic')
            ->with('success', 'Your account has been deleted.');
    }
}
