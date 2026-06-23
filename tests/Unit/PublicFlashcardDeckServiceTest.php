<?php

namespace Tests\Unit;

use App\Models\Question;
use App\Models\StudySession;
use App\Models\User;
use App\Services\GuestService;
use App\Services\PublicFlashcardDeckService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicFlashcardDeckServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommended_decks_include_general_and_weakest_categories(): void
    {
        $this->seedQuestions('Airway', 5);
        $this->seedQuestions('Trauma', 5);
        $this->seedQuestions('Medical', 5);

        $service = app(PublicFlashcardDeckService::class);
        $request = $this->guestRequest();

        $categoryStats = collect([
            (object) ['category' => 'Airway', 'accuracy_percent' => 40, 'total' => 10],
            (object) ['category' => 'Trauma', 'accuracy_percent' => 55, 'total' => 8],
            (object) ['category' => 'Medical', 'accuracy_percent' => 70, 'total' => 12],
        ]);

        $decks = $service->recommendedDecks($request, CertificationLevel::EMT_BASIC, $categoryStats);

        $this->assertSame(4, $decks->count());
        $this->assertTrue($decks->first()->is_general);
        $this->assertSame(['Airway', 'Trauma', 'Medical'], $decks->skip(1)->pluck('label')->all());
    }

    public function test_recommended_decks_include_extra_bank_categories_without_quiz_stats(): void
    {
        foreach (['Airway', 'Trauma', 'Medical', 'Operations', 'Pharmacology'] as $category) {
            $this->seedQuestions($category, 5);
        }

        $decks = app(PublicFlashcardDeckService::class)->recommendedDecks(
            $this->guestRequest(),
            CertificationLevel::EMT_BASIC,
            collect(),
        );

        $this->assertSame(6, $decks->count());
        $this->assertTrue($decks->first()->is_general);
        $this->assertSame(
            ['Airway', 'Medical', 'Operations', 'Pharmacology', 'Trauma'],
            $decks->skip(1)->pluck('label')->sort()->values()->all(),
        );
    }

    public function test_completed_public_deck_is_marked_for_guest(): void
    {
        $this->seedQuestions('Airway', 5);

        $guestToken = (string) Str::uuid();

        StudySession::query()->create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'public_deck_key' => PublicFlashcardDeckService::GENERAL_KEY,
            'deck' => [1],
            'initial_deck_size' => 1,
            'status' => StudySession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $request = Request::create('/emt-basic/study', 'GET');
        $session = $this->app['session']->driver();
        $session->start();
        $request->setLaravelSession($session);
        $session->put(GuestService::SESSION_KEY, $guestToken);

        $decks = app(PublicFlashcardDeckService::class)->recommendedDecks(
            $request,
            CertificationLevel::EMT_BASIC,
            collect(),
        );

        $this->assertTrue($decks->first()->completed);
    }

    public function test_completed_focus_public_deck_is_marked_for_signed_in_user(): void
    {
        $this->seedQuestions('Airway', 5);

        $user = User::factory()->create();

        StudySession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'public_deck_key' => 'Airway',
            'filter_category' => 'Airway',
            'deck' => [1],
            'initial_deck_size' => 1,
            'status' => StudySession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        $request = Request::create('/emt-basic/study', 'GET');
        $request->setUserResolver(fn () => $user);

        $decks = app(PublicFlashcardDeckService::class)->recommendedDecks(
            $request,
            CertificationLevel::EMT_BASIC,
            collect([
                (object) ['category' => 'Airway', 'accuracy_percent' => 40, 'total' => 10],
            ]),
        );

        $airwayDeck = $decks->firstWhere('label', 'Airway');

        $this->assertNotNull($airwayDeck);
        $this->assertTrue($airwayDeck->completed);
    }

    private function guestRequest(): Request
    {
        $request = Request::create('/emt-basic/study', 'GET');
        $session = $this->app['session']->driver();
        $session->start();
        $request->setLaravelSession($session);
        $session->put(GuestService::SESSION_KEY, (string) Str::uuid());

        return $request;
    }

    private function seedQuestions(string $category, int $count): void
    {
        for ($index = 0; $index < $count; $index++) {
            Question::query()->create([
                'source_key' => 'public-deck-'.Str::lower($category).'-'.$index,
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => $category,
                'difficulty' => 2,
                'initial_difficulty' => 2,
                'stem' => 'Question '.$index,
                'option_a' => 'Answer A',
                'option_b' => 'Answer B',
                'option_c' => 'Answer C',
                'option_d' => 'Answer D',
                'correct_option' => 'A',
                'explanation' => 'Because A.',
            ]);
        }
    }
}
