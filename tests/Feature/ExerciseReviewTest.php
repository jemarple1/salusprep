<?php

namespace Tests\Feature;

use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use App\Support\ReviewContent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExerciseReviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_exercise_has_a_linked_review_concept(): void
    {
        foreach (CertificationLevel::all() as $level) {
            foreach (PlatformExercise::forLevel($level) as $exercise) {
                $concept = ReviewContent::forExercise($level, $exercise['slug']);

                $this->assertNotNull(
                    $concept,
                    "Missing review concept for {$level}/{$exercise['slug']}",
                );
                $this->assertNotEmpty($concept['sources'] ?? [], "Review concept {$exercise['slug']} needs .gov sources");
            }
        }
    }

    public function test_exercise_page_shows_how_to_and_review_link(): void
    {
        $response = $this->get('/emt-basic/exercises/soap-charting');

        $response->assertOk();
        $response->assertSee('How this exercise works');
        $response->assertSee('Fundamental concept');
        $response->assertSee('SOAP Documentation');
        $response->assertSee('/emt-basic/review/soap-charting', false);
    }

    public function test_exercise_page_has_seo_meta_description(): void
    {
        $response = $this->get('/emt-basic/exercises/triage-start');

        $response->assertOk();
        $response->assertSee('START Triage — Triage Skill Exercise', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('Interactive START Triage drill', false);
    }

    public function test_skills_index_has_seo_title(): void
    {
        $response = $this->get('/emt-basic/skills');

        $response->assertOk();
        $response->assertSee('Skills &amp; Exercises', false);
        $response->assertSee('SOAP charting', false);
    }

    public function test_review_show_links_back_to_exercise_when_applicable(): void
    {
        $response = $this->get('/emt-basic/review/soap-charting');

        $response->assertOk();
        $response->assertSee('Practice this skill');
        $response->assertSee('/emt-basic/exercises/soap-charting', false);
    }

    public function test_nclex_exercise_reuses_base_review_article(): void
    {
        $concept = ReviewContent::forExercise(CertificationLevel::NCLEX_PN, 'medication-rights');

        $this->assertSame('medication-rights', $concept['slug']);
        $this->assertSame('Medication Safety & the Nine Rights', $concept['title']);
    }

    public function test_emt_basic_review_index_includes_exercise_concepts(): void
    {
        $response = $this->get('/emt-basic/review');

        $response->assertOk();
        $response->assertSee('Primary Assessment');
        $response->assertSee('START Triage');
        $response->assertSee('SOAP Documentation');
    }
}
