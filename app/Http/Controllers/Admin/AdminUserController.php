<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class AdminUserController extends Controller
{
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
