<?php

namespace App\Services;

use App\Models\Question;
use App\Models\StudySession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PublicFlashcardDeckService
{
    public const GENERAL_KEY = 'general';

    public const GENERAL_LABEL = 'General knowledge';

    public const TAILORED_LIMIT = 6;

    public const DECK_SIZE = 25;

    public function __construct(private GuestService $guests) {}

    /**
     * @return Collection<int, object{
     *     key: string,
     *     label: string,
     *     category: ?string,
     *     is_general: bool,
     *     card_count: int,
     *     accuracy_percent: ?int,
     *     completed: bool,
     * }>
     */
    public function recommendedDecks(
        Request $request,
        string $certificationLevel,
        Collection $categoryStats,
    ): Collection {
        $completed = $this->completedDeckKeys($request, $certificationLevel);

        $generalCount = $this->availableQuestionCount($certificationLevel);

        $general = (object) [
            'key' => self::GENERAL_KEY,
            'label' => self::GENERAL_LABEL,
            'category' => null,
            'is_general' => true,
            'card_count' => min(self::DECK_SIZE, $generalCount),
            'accuracy_percent' => null,
            'completed' => in_array(self::GENERAL_KEY, $completed, true),
        ];

        $statsByCategory = $categoryStats->keyBy('category');

        $rankedCategories = Question::query()
            ->where('certification_level', $certificationLevel)
            ->distinct()
            ->orderBy('category')
            ->pluck('category')
            ->sortBy(function (string $category) use ($statsByCategory) {
                $stat = $statsByCategory->get($category);

                return [$stat !== null ? $stat->accuracy_percent : 1000, $category];
            })
            ->values();

        $focusCategories = $rankedCategories
            ->when(
                $statsByCategory->isNotEmpty(),
                fn ($categories) => $categories->take(self::TAILORED_LIMIT),
                fn ($categories) => $categories->take(self::TAILORED_LIMIT + 3),
            );

        $tailored = $focusCategories
            ->map(function (string $category) use ($certificationLevel, $completed, $statsByCategory) {
                $stat = $statsByCategory->get($category);
                $count = $this->availableQuestionCount($certificationLevel, $category);

                return (object) [
                    'key' => $category,
                    'label' => $category,
                    'category' => $category,
                    'is_general' => false,
                    'card_count' => min(self::DECK_SIZE, $count),
                    'accuracy_percent' => $stat?->accuracy_percent,
                    'completed' => in_array($category, $completed, true),
                ];
            })
            ->filter(fn ($deck) => $deck->card_count > 0)
            ->values();

        return collect([$general])->concat($tailored)->filter(fn ($deck) => $deck->card_count > 0)->values();
    }

    /** @return list<string> */
    public function completedDeckKeys(Request $request, string $certificationLevel): array
    {
        $query = StudySession::query()
            ->where('certification_level', $certificationLevel)
            ->where('status', StudySession::STATUS_COMPLETED)
            ->whereNotNull('public_deck_key');

        $user = $request->user();

        if ($user instanceof User) {
            $query->where('user_id', $user->id);
        } else {
            $guestToken = $this->guests->token($request);
            $deviceId = $this->guests->deviceId($request);

            $query->where(function ($inner) use ($guestToken, $deviceId) {
                $inner->where('guest_token', $guestToken)
                    ->orWhere('device_id', $deviceId);
            });
        }

        return $query
            ->distinct()
            ->pluck('public_deck_key')
            ->filter(fn ($key) => is_string($key) && $key !== '')
            ->values()
            ->all();
    }

    public function availableQuestionCount(string $certificationLevel, ?string $category = null): int
    {
        $query = Question::query()->where('certification_level', $certificationLevel);

        if ($category !== null) {
            $query->where('category', $category);
        }

        return (int) $query->count();
    }

    public function isValidDeckKey(string $certificationLevel, string $deckKey): bool
    {
        if ($deckKey === self::GENERAL_KEY) {
            return $this->availableQuestionCount($certificationLevel) > 0;
        }

        return Question::query()
            ->where('certification_level', $certificationLevel)
            ->where('category', $deckKey)
            ->exists();
    }
}
