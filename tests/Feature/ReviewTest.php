<?php

namespace Tests\Feature;

use App\Support\CertificationLevel;
use App\Support\ReviewContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_each_platform_has_foundational_and_exercise_review_concepts(): void
    {
        $expectedMinimum = [
            CertificationLevel::EMT_BASIC => 19,
            CertificationLevel::EMT_ADVANCED => 5,
            CertificationLevel::NCLEX_PN => 12,
            CertificationLevel::PARAMEDIC => 20,
        ];

        foreach (CertificationLevel::all() as $level) {
            $concepts = ReviewContent::forLevel($level);
            $this->assertGreaterThanOrEqual(
                $expectedMinimum[$level],
                count($concepts),
                "Expected at least {$expectedMinimum[$level]} review concepts for {$level}",
            );
        }
    }

    public function test_review_index_lists_concepts_for_emt_basic(): void
    {
        $response = $this->get('/emt-basic/review');

        $response->assertOk();
        $response->assertSee('Review');
        $response->assertSee('Primary Assessment');
        $response->assertSee('name="q"', false);
    }

    public function test_review_search_filters_topics(): void
    {
        $response = $this->get('/emt-basic/review?q=airway');

        $response->assertOk();
        $response->assertSee('Airway &amp; Breathing', false);
        $response->assertDontSee('Scope of Practice');
    }

    public function test_review_show_displays_concept_and_sources(): void
    {
        $response = $this->get('/emt-basic/review/primary-assessment');

        $response->assertOk();
        $response->assertSee('The Primary Assessment');
        $response->assertSee('NHTSA');
        $response->assertSee('cdc.gov/fieldtriage', false);
        $response->assertSee('id="source-1"', false);
    }

    public function test_review_show_returns_404_for_unknown_slug(): void
    {
        $this->get('/nclex-pn/review/not-a-topic')->assertNotFound();
    }

    public function test_nclex_review_has_nursing_topics(): void
    {
        $response = $this->get('/nclex-pn/review');

        $response->assertOk();
        $response->assertSee('Infection Prevention');
        $response->assertSee('Medication Safety');
    }

    public function test_review_show_links_exercise_card_for_exercise_concepts(): void
    {
        $response = $this->get('/emt-basic/review/soap-charting');

        $response->assertOk();
        $response->assertSee('Practice this skill');
        $response->assertSee('SOAP Exercise');
        $response->assertSee('/emt-basic/exercises/soap-charting', false);
    }

    public function test_review_show_links_exercise_card_for_reused_review_slug(): void
    {
        $response = $this->get('/nclex-pn/review/medication-rights');

        $response->assertOk();
        $response->assertSee('Practice this skill');
        $response->assertSee('Medication Rights');
        $response->assertSee('/nclex-pn/exercises/medication-rights', false);
    }

    public function test_learn_urls_redirect_or_are_gone(): void
    {
        $this->get('/emt-basic/learn')->assertNotFound();
    }
}
