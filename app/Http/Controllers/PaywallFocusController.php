<?php

namespace App\Http\Controllers;

use App\Services\FocusCategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PaywallFocusController extends Controller
{
    public function __construct(
        private FocusCategoryService $focusCategory,
    ) {}

    public function __invoke(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:100'],
        ]);

        $level = $request->attributes->get('certification_level');

        $this->focusCategory->pin($request, $level, $validated['category']);

        if ($request->expectsJson()) {
            return response()->json(['category' => $validated['category']]);
        }

        return back()->with('focus_saved', true);
    }
}
