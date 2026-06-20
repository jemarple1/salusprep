<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\PreviewAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminSettingsController extends Controller
{
    public function updatePreviewLimit(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'preview_minutes_limit' => ['required', 'integer', 'min:1', 'max:1440'],
        ]);

        Setting::set(
            PreviewAccessService::MINUTES_KEY,
            (string) $validated['preview_minutes_limit'],
        );

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Preview minute limit updated.');
    }
}
