<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParamedicExerciseTest extends TestCase
{
    use RefreshDatabase;

    public function test_paramedic_skills_index_lists_exercises(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('skills.index', CertificationLevel::slug(CertificationLevel::PARAMEDIC)))
            ->assertOk()
            ->assertSee('Patient Assessment & Clinical Decision Making')
            ->assertSee('Adaptive NRP Readiness Exam')
            ->assertDontSee('coming soon');
    }

    public function test_paramedic_exercise_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'patient-assessment',
            ]))
            ->assertOk()
            ->assertSee('Patient Assessment')
            ->assertSee('Work through each decision');
    }

    public function test_paramedic_matching_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'rhythm-12lead',
            ]))
            ->assertOk()
            ->assertSee('Check matches');
    }

    public function test_paramedic_numerical_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'pharmacology-mastery',
            ]))
            ->assertOk()
            ->assertSee('Check answer');
    }

    public function test_paramedic_multi_select_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'shock-hemodynamics',
            ]))
            ->assertOk()
            ->assertSee('Select all findings');
    }

    public function test_paramedic_categorization_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'airway-respiratory',
            ]))
            ->assertOk()
            ->assertSee('Check categories');
    }

    public function test_paramedic_stroke_exercise_loads_fast_ui(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'stroke-neurology',
            ]))
            ->assertOk()
            ->assertSee('Face droop');
    }

    public function test_paramedic_soap_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'soap-charting',
            ]))
            ->assertOk()
            ->assertSee('Subjective');
    }

    public function test_paramedic_mci_exercise_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'ems-operations-mci',
            ]))
            ->assertOk()
            ->assertSee('Patient A');
    }
}
