<?php

namespace App\Services;

use App\Models\Question;
use App\Models\SectionAccess;
use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FocusCategoryService
{
    public const SESSION_PREFIX = 'paywall.pinned_focus.';

    public function get(Request $request, string $certificationLevel): ?string
    {
        if ($request->user() !== null) {
            $access = SectionAccess::query()
                ->where('user_id', $request->user()->id)
                ->where('certification_level', $certificationLevel)
                ->first();

            if ($access?->pinned_focus_category !== null) {
                return $access->pinned_focus_category;
            }
        }

        $sessionValue = $request->session()->get(self::SESSION_PREFIX.$certificationLevel);

        return is_string($sessionValue) && $sessionValue !== '' ? $sessionValue : null;
    }

    public function pin(Request $request, string $certificationLevel, string $category): void
    {
        abort_unless($this->isValidCategory($certificationLevel, $category), 422);

        $request->session()->put(self::SESSION_PREFIX.$certificationLevel, $category);

        if ($request->user() !== null) {
            SectionAccess::updateOrCreate(
                [
                    'user_id' => $request->user()->id,
                    'certification_level' => $certificationLevel,
                ],
                ['pinned_focus_category' => $category],
            );
        }
    }

    public function persistSessionToUser(Request $request, User $user): void
    {
        foreach (CertificationLevel::all() as $level) {
            $category = $request->session()->get(self::SESSION_PREFIX.$level);

            if (! is_string($category) || $category === '') {
                continue;
            }

            if (! $this->isValidCategory($level, $category)) {
                continue;
            }

            SectionAccess::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'certification_level' => $level,
                ],
                ['pinned_focus_category' => $category],
            );
        }
    }

    public function isValidCategory(string $certificationLevel, string $category): bool
    {
        return Question::query()
            ->where('certification_level', $certificationLevel)
            ->where('category', $category)
            ->exists();
    }

    /**
     * @param  Collection<int, object{category: string}>  $options
     */
    public function resolvePinned(Request $request, string $certificationLevel, Collection $options): ?string
    {
        if ($options->isEmpty()) {
            return $this->get($request, $certificationLevel);
        }

        $pinned = $this->get($request, $certificationLevel);
        $optionCategories = $options->pluck('category');

        if ($pinned !== null && $optionCategories->contains($pinned)) {
            return $pinned;
        }

        $default = $options->first()->category;
        $this->pin($request, $certificationLevel, $default);

        return $default;
    }
}
