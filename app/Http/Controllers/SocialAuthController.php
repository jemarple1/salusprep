<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\GuestService;
use App\Services\LogSnagService;
use App\Services\SignupGeoService;
use App\Support\CertificationLevel;
use App\Support\UserAvatar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

class SocialAuthController extends Controller
{
    public const INTENT_SESSION_KEY = 'social_auth.intent';

    public const SECTION_SESSION_KEY = 'social_auth.section';

    /** @var list<string> */
    private const PROVIDERS = ['google', 'facebook', 'twitter'];

    public function __construct(
        private GuestService $guests,
        private SignupGeoService $signupGeo,
        private LogSnagService $logSnag,
    ) {}

    public function redirect(Request $request, string $provider): RedirectResponse
    {
        $this->assertProvider($provider);

        $intent = $request->query('intent', 'register');

        if (! in_array($intent, ['register'], true)) {
            $intent = 'register';
        }

        $section = $request->query('section');

        $request->session()->put(self::INTENT_SESSION_KEY, $intent);

        if (is_string($section) && CertificationLevel::isValidSlug($section)) {
            $request->session()->put(self::SECTION_SESSION_KEY, $section);
        }

        return Socialite::driver($this->socialiteDriver($provider))->redirect();
    }

    public function callback(Request $request, string $provider): RedirectResponse
    {
        $this->assertProvider($provider);

        $socialUser = Socialite::driver($this->socialiteDriver($provider))->user();

        $intent = $request->session()->pull(self::INTENT_SESSION_KEY, 'register');
        $sectionSlug = $request->session()->pull(self::SECTION_SESSION_KEY);

        if (! is_string($sectionSlug) || ! CertificationLevel::isValidSlug($sectionSlug)) {
            $sectionSlug = CertificationLevel::slug(CertificationLevel::EMT_BASIC);
        }

        $user = $this->findOrCreateUser($provider, $socialUser, $request);

        Auth::login($user, remember: true);

        $request->session()->regenerate();

        $user->update(['last_login_at' => now()]);

        $this->guests->mergeIntoUser($request, $user);

        $level = CertificationLevel::fromSlug($sectionSlug);

        if ($level !== null && ! $user->hasSectionAccess($level)) {
            return redirect()->route('platform.checkout', $sectionSlug);
        }

        return redirect()->route('platform.home', $sectionSlug);
    }

    private function findOrCreateUser(string $provider, SocialiteUser $socialUser, Request $request): User
    {
        $providerIdColumn = $this->providerIdColumn($provider);

        $existing = User::query()->where($providerIdColumn, $socialUser->getId())->first();

        if ($existing !== null) {
            return $existing;
        }

        $byEmail = User::query()->where('email', $socialUser->getEmail())->first();

        if ($byEmail !== null) {
            $byEmail->update([$providerIdColumn => $socialUser->getId()]);

            return $byEmail->fresh();
        }

        $user = User::create([
            'name' => $socialUser->getName() ?: ($socialUser->getNickname() ?: 'SalusPrep User'),
            'avatar_color' => UserAvatar::randomColor(),
            'email' => $socialUser->getEmail(),
            'password' => Hash::make(Str::random(32)),
            'terms_accepted_at' => now(),
            'marketing_emails_opt_in' => false,
            $providerIdColumn => $socialUser->getId(),
            ...$this->signupGeo->fromRequest($request),
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));

        $this->logSnag->notifySignup($user);

        return $user;
    }

    private function assertProvider(string $provider): void
    {
        abort_unless(in_array($provider, self::PROVIDERS, true), 404);
    }

    private function socialiteDriver(string $provider): string
    {
        return $provider === 'twitter' ? 'twitter-oauth-2' : $provider;
    }

    private function providerIdColumn(string $provider): string
    {
        return match ($provider) {
            'google' => 'google_id',
            'facebook' => 'facebook_id',
            'twitter' => 'twitter_id',
        };
    }
}
