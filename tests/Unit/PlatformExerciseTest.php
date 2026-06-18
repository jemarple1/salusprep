<?php

namespace Tests\Unit;

use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use Tests\TestCase;

class PlatformExerciseTest extends TestCase
{
    public function test_emt_basic_exercises_have_five_scenarios_each(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::EMT_BASIC) as $exercise) {
            $this->assertCount(5, PlatformExercise::scenarios(CertificationLevel::EMT_BASIC, $exercise['slug']));
        }
    }

    public function test_first_scenario_is_free_for_guests(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::EMT_BASIC) as $exercise) {
            $this->assertTrue(PlatformExercise::canAccessScenario(null, CertificationLevel::EMT_BASIC, false, 0));
            $this->assertFalse(PlatformExercise::canAccessScenario(null, CertificationLevel::EMT_BASIC, false, 1));
        }
    }
}
