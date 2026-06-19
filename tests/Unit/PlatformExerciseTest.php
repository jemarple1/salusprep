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

    public function test_scenario_access_follows_platform_access_flag(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::EMT_BASIC) as $exercise) {
            $this->assertTrue(PlatformExercise::canAccessScenario(true));
            $this->assertFalse(PlatformExercise::canAccessScenario(false));
        }
    }
}
