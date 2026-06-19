<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Support\CertificationLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function unlock(Request $request, User $user, AdaptiveExamService $examService): RedirectResponse
    {
        $validated = $request->validate([
            'certification_level' => ['required', 'string', Rule::in(CertificationLevel::all())],
        ]);

        $level = $validated['certification_level'];
        $examService->unlockSection($user, $level);

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Unlocked '.CertificationLevel::label($level)." for {$user->name}.");
    }

    public function destroy(User $user): RedirectResponse
    {
        $name = $user->name;
        $email = $user->email;

        DB::transaction(function () use ($user, $email) {
            DB::table('sessions')->where('user_id', $user->id)->delete();
            DB::table('password_reset_tokens')->where('email', $email)->delete();

            $user->delete();
        });

        return redirect()
            ->route('admin.dashboard')
            ->with('success', "Deleted {$name} ({$email}) and all related purchases, unlocks, and activity.");
    }
}
