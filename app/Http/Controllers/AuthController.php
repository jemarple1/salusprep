<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\GuestService;
use App\Services\LogSnagService;
use App\Services\PreviewAccessService;
use App\Services\SignupGeoService;
use App\Support\CertificationLevel;
use App\Support\UserAvatar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public const PAYWALL_CHECKOUT_SESSION_KEY = 'paywall.auto_checkout_section';

    public const REGISTER_SECTION_SESSION_KEY = 'register.source_section';

    public function __construct(
        private GuestService $guests,
        private SignupGeoService $signupGeo,
        private LogSnagService $logSnag,
        private PreviewAccessService $preview,
    ) {}

    public function showRegister(Request $request): View
    {
        $sectionSlug = $request->query('section');

        if (! is_string($sectionSlug) || $sectionSlug === '') {
            $sectionSlug = $request->session()->get(self::PAYWALL_CHECKOUT_SESSION_KEY);
        }

        if (! is_string($sectionSlug) || $sectionSlug === '') {
            $sectionSlug = $request->session()->get(self::REGISTER_SECTION_SESSION_KEY);
        }

        if (! is_string($sectionSlug) || ! CertificationLevel::isValidSlug($sectionSlug)) {
            $sectionSlug = CertificationLevel::slug(CertificationLevel::EMT_BASIC);
        }

        $sectionOptions = collect(CertificationLevel::slugs())
            ->map(fn (string $slug, string $level) => [
                'slug' => $slug,
                'label' => CertificationLevel::label($level),
                'price' => SectionPricing::formatted(),
            ])
            ->values();

        return view('auth.register', [
            'sectionOptions' => $sectionOptions,
            'defaultSectionSlug' => $sectionSlug,
            'unlockPrice' => SectionPricing::formatted(),
            'previewMinutes' => $this->preview->minutesLimit(),
            'preselectUnlock' => $request->boolean('unlock'),
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['required', 'accepted'],
            'marketing_emails_opt_in' => ['sometimes', 'boolean'],
            'signup_plan' => ['required', 'in:free,unlock'],
            'unlock_section' => ['required_if:signup_plan,unlock', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'avatar_color' => UserAvatar::randomColor(),
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'terms_accepted_at' => now(),
            'marketing_emails_opt_in' => $request->boolean('marketing_emails_opt_in'),
            ...$this->signupGeo->fromRequest($request),
        ]);

        Auth::login($user);

        Mail::to($user->email)->send(new WelcomeMail($user));

        $this->logSnag->notifySignup($user);

        return $this->redirectAfterAuth($request, $user);
    }

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $request->user()->update(['last_login_at' => now()]);

            return $this->redirectAfterAuth($request, $request->user());
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->onlyInput('email');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('platform.home', 'emt-basic');
    }

    private function redirectAfterAuth(Request $request, User $user): RedirectResponse
    {
        $this->guests->mergeIntoUser($request, $user);

        if ($request->input('signup_plan') === 'unlock') {
            $sectionSlug = $request->input('unlock_section');

            if (is_string($sectionSlug) && $sectionSlug !== '') {
                $level = CertificationLevel::fromSlug($sectionSlug);

                if ($level !== null && ! $user->hasSectionAccess($level)) {
                    return redirect()->route('platform.checkout', $sectionSlug);
                }

                if ($level !== null && $user->hasSectionAccess($level)) {
                    return redirect()
                        ->route('platform.welcome', $sectionSlug)
                        ->with('success', CertificationLevel::label($level).' is already unlocked.');
                }
            }
        }

        $autoCheckoutSlug = $request->session()->pull(self::PAYWALL_CHECKOUT_SESSION_KEY);

        if (is_string($autoCheckoutSlug) && $autoCheckoutSlug !== '') {
            return redirect()->route('platform.home', $autoCheckoutSlug);
        }

        $sourceSection = $request->session()->get(self::REGISTER_SECTION_SESSION_KEY);

        if (is_string($sourceSection) && $sourceSection !== '' && CertificationLevel::isValidSlug($sourceSection)) {
            return redirect()->route('platform.home', $sourceSection);
        }

        return redirect()->intended(route('platform.home', 'emt-basic'));
    }
}
