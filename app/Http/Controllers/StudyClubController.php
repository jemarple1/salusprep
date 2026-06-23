<?php

namespace App\Http\Controllers;

use App\Services\PreviewAccessService;
use App\Services\StudyClubService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudyClubController extends Controller
{
    public function __construct(
        private StudyClubService $studyClub,
        private PreviewAccessService $preview,
    ) {}

    public function join(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');

        if (! is_string($level) || $this->preview->isUnlocked($request, $level)) {
            return redirect()->back();
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $this->studyClub->join($request, $validated['email'], $level);

        return redirect()
            ->back()
            ->with('success', 'Welcome to Study Pass — your free preview access is unlocked.');
    }

    public function unsubscribe(string $token): View
    {
        $member = $this->studyClub->unsubscribe($token);

        abort_unless($member !== null, 404);

        return view('email.study-club-unsubscribed', [
            'email' => $member->email,
        ]);
    }
}
