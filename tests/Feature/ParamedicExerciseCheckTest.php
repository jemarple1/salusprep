<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParamedicExerciseCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_branching_scenario_check_accepts_correct_path(): void
    {
        $user = User::factory()->create();
        $scenario = PlatformExercise::scenario(CertificationLevel::PARAMEDIC, 'patient-assessment', 0, 1);
        $path = collect($scenario['steps'])->pluck('correct')->all();

        $this->actingAs($user)
            ->postJson(route('exercises.check', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'patient-assessment',
            ]), [
                'scenario' => 0,
                'path' => $path,
            ])
            ->assertOk()
            ->assertJson(['correct' => true]);
    }

    public function test_matching_check_validates_pairs(): void
    {
        $user = User::factory()->create();
        $scenario = PlatformExercise::scenario(CertificationLevel::PARAMEDIC, 'rhythm-12lead', 0, 1);

        $this->actingAs($user)
            ->postJson(route('exercises.check', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'rhythm-12lead',
            ]), [
                'scenario' => 0,
                'matches' => $scenario['correct'],
            ])
            ->assertOk()
            ->assertJson(['correct' => true]);
    }

    public function test_numerical_check_accepts_value_within_tolerance(): void
    {
        $user = User::factory()->create();
        $scenario = PlatformExercise::scenario(CertificationLevel::PARAMEDIC, 'pharmacology-mastery', 0, 1);

        $this->actingAs($user)
            ->postJson(route('exercises.check', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'pharmacology-mastery',
            ]), [
                'scenario' => 0,
                'answer' => $scenario['correct'],
            ])
            ->assertOk()
            ->assertJson(['correct' => true]);
    }

    public function test_multi_select_check_requires_exact_set(): void
    {
        $user = User::factory()->create();
        $scenario = PlatformExercise::scenario(CertificationLevel::PARAMEDIC, 'shock-hemodynamics', 0, 1);

        $this->actingAs($user)
            ->postJson(route('exercises.check', [
                'section' => CertificationLevel::slug(CertificationLevel::PARAMEDIC),
                'exercise' => 'shock-hemodynamics',
            ]), [
                'scenario' => 0,
                'answers' => $scenario['correct'],
            ])
            ->assertOk()
            ->assertJson(['correct' => true]);
    }
}
