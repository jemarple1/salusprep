<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\StudySession;
use App\Services\GuestService;
use App\Services\PublicFlashcardDeckService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class PublicFlashcardDeckCarouselTest extends TestCase
{
    use RefreshDatabase;

    public function test_study_page_shows_public_deck_carousel_with_completion_checkmark(): void
    {
        for ($index = 0; $index < 5; $index++) {
            Question::query()->create([
                'source_key' => 'study-public-'.$index,
                'certification_level' => CertificationLevel::EMT_BASIC,
                'category' => 'Airway',
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

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->get('/emt-basic/study')
            ->assertOk()
            ->assertSee('Focus decks')
            ->assertSee('General knowledge')
            ->assertSee('Your deck')
            ->assertSee('Complete')
            ->assertSee('flashcard-deck-carousel', false);
    }

    public function test_paywall_restores_focus_exam_picker(): void
    {
        $guestToken = (string) Str::uuid();

        $this->withSession([GuestService::SESSION_KEY => $guestToken])
            ->get('/emt-basic/unlock')
            ->assertOk()
            ->assertSee('choose your next quiz', false)
            ->assertSee('After Preview, we', false);
    }
}
