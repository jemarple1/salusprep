<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DailyStudyEmailController extends Controller
{
    public function unsubscribe(Request $request, User $user): View
    {
        abort_unless($request->hasValidSignature(), 403);

        $user->daily_study_email_opt_in = false;
        $user->save();

        return view('email.daily-study-unsubscribed', [
            'user' => $user,
        ]);
    }
}
