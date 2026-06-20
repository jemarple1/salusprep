<?php

namespace Tests\Feature;

use App\Models\User;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NclexExerciseTest extends TestCase
{
    use RefreshDatabase;

    public function test_nclex_skills_index_lists_exercises(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('skills.index', CertificationLevel::slug(CertificationLevel::NCLEX_PN)))
            ->assertOk()
            ->assertSee('ABC Prioritization')
            ->assertSee('Morse Fall Scale');
    }

    public function test_nclex_exercise_page_loads(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::NCLEX_PN),
                'exercise' => 'abc-prioritization',
            ]))
            ->assertOk()
            ->assertSee('ABC Prioritization');
    }

    public function test_nclex_adpie_exercise_loads_sort_ui(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('exercises.show', [
                'section' => CertificationLevel::slug(CertificationLevel::NCLEX_PN),
                'exercise' => 'adpie-nursing-process',
            ]))
            ->assertOk()
            ->assertSee('ADPIE');
    }
}
