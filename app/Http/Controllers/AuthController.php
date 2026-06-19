<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\GuestService;
use App\Services\SignupGeoService;
use App\Support\CertificationLevel;
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

    public function __construct(
        private GuestService $guests,
        private SignupGeoService $signupGeo,
    ) {}

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['required', 'accepted'],
            'marketing_emails_opt_in' => ['sometimes', 'boolean'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'terms_accepted_at' => now(),
            'marketing_emails_opt_in' => $request->boolean('marketing_emails_opt_in'),
            ...$this->signupGeo->fromRequest($request),
        ]);

        Auth::login($user);

        Mail::to($user->email)->send(new WelcomeMail($user));

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

        $autoCheckoutSlug = $request->session()->pull(self::PAYWALL_CHECKOUT_SESSION_KEY);

        if (is_string($autoCheckoutSlug) && $autoCheckoutSlug !== '') {
            $level = CertificationLevel::fromSlug($autoCheckoutSlug);

            if ($level !== null && ! $user->hasSectionAccess($level)) {
                return redirect()->route('platform.checkout', $autoCheckoutSlug);
            }
        }

        return redirect()->intended(route('platform.home', 'emt-basic'));
    }
}
