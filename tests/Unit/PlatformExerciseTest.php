<?php

namespace Tests\Unit;

use App\Support\BurnRegion;
use App\Support\CertificationLevel;
use App\Support\PlatformExercise;
use App\Services\ExerciseProgressService;
use Tests\TestCase;

class PlatformExerciseTest extends TestCase
{
    public function test_emt_basic_exercises_have_ten_scenarios_each(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::EMT_BASIC) as $exercise) {
            $this->assertCount(
                ExerciseProgressService::SCENARIOS_PER_EXERCISE,
                PlatformExercise::scenarios(CertificationLevel::EMT_BASIC, $exercise['slug']),
                $exercise['slug'],
            );
        }
    }

    public function test_scenario_access_follows_platform_access_flag(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::EMT_BASIC) as $exercise) {
            $this->assertTrue(PlatformExercise::canAccessScenario(true));
            $this->assertFalse(PlatformExercise::canAccessScenario(false));
        }
    }

    public function test_burn_regions_normalize_legacy_keys_to_sided_keys(): void
    {
        $normalized = BurnRegion::normalizeKeys(['chest', 'arm_l', 'back']);

        $this->assertSame(
            ['anterior:arm_l', 'anterior:chest', 'posterior:arm_l', 'posterior:back'],
            $normalized,
        );
    }

    public function test_addon_soap_scenarios_include_sections(): void
    {
        $scenario = PlatformExercise::scenario(CertificationLevel::EMT_BASIC, 'soap-charting', 9);

        $this->assertNotNull($scenario);
        $this->assertArrayHasKey('sections', $scenario);
        $this->assertArrayHasKey('sentences', $scenario);
    }

    public function test_addon_pharma_assist_scenarios_include_options(): void
    {
        $scenario = PlatformExercise::scenario(CertificationLevel::EMT_BASIC, 'pharma-assist', 8);

        $this->assertNotNull($scenario);
        $this->assertArrayHasKey('options', $scenario);
        $this->assertContains($scenario['correct'], ['assist', 'withhold']);
    }

    public function test_soap_level_one_includes_trash_sentences(): void
    {
        $scenario = PlatformExercise::scenario(CertificationLevel::EMT_BASIC, 'soap-charting', 0, 1);

        $this->assertNotNull($scenario);
        $trash = collect($scenario['sentences'])->where('section', 'X');
        $this->assertGreaterThanOrEqual(2, $trash->count());
    }

    public function test_soap_has_five_levels_with_increasing_difficulty(): void
    {
        $this->assertSame(5, PlatformExercise::exerciseLevelCount(CertificationLevel::EMT_BASIC, 'soap-charting'));

        $levelOneCount = count(PlatformExercise::scenario(CertificationLevel::EMT_BASIC, 'soap-charting', 0, 1)['sentences']);
        $levelFiveCount = count(PlatformExercise::scenario(CertificationLevel::EMT_BASIC, 'soap-charting', 0, 5)['sentences']);

        $this->assertGreaterThan($levelOneCount, $levelFiveCount);
        $this->assertCount(10, PlatformExercise::scenarios(CertificationLevel::EMT_BASIC, 'soap-charting', 3));
    }

    public function test_nclex_pn_exercises_have_ten_scenarios_per_level(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::NCLEX_PN) as $exercise) {
            $this->assertSame(5, PlatformExercise::exerciseLevelCount(CertificationLevel::NCLEX_PN, $exercise['slug']));

            for ($level = 1; $level <= 5; $level++) {
                $this->assertCount(
                    ExerciseProgressService::SCENARIOS_PER_EXERCISE,
                    PlatformExercise::scenarios(CertificationLevel::NCLEX_PN, $exercise['slug'], $level),
                    $exercise['slug'].' level '.$level,
                );
            }
        }
    }

    public function test_nclex_adpie_scenarios_include_sections(): void
    {
        $scenario = PlatformExercise::scenario(CertificationLevel::NCLEX_PN, 'adpie-nursing-process', 0, 1);

        $this->assertNotNull($scenario);
        $this->assertArrayHasKey('sections', $scenario);
        $this->assertArrayHasKey('sentences', $scenario);
    }

    public function test_nclex_maslow_difficulty_increases_item_count(): void
    {
        $levelOne = PlatformExercise::scenario(CertificationLevel::NCLEX_PN, 'maslow-prioritization', 0, 1);
        $levelFive = PlatformExercise::scenario(CertificationLevel::NCLEX_PN, 'maslow-prioritization', 0, 5);

        $this->assertCount(4, $levelOne['items']);
        $this->assertCount(5, $levelFive['items']);
    }

    public function test_paramedic_exercises_have_ten_scenarios_per_level(): void
    {
        foreach (PlatformExercise::forLevel(CertificationLevel::PARAMEDIC) as $exercise) {
            $levelCount = PlatformExercise::exerciseLevelCount(CertificationLevel::PARAMEDIC, $exercise['slug']);

            for ($level = 1; $level <= max(1, $levelCount); $level++) {
                $this->assertCount(
                    ExerciseProgressService::SCENARIOS_PER_EXERCISE,
                    PlatformExercise::scenarios(CertificationLevel::PARAMEDIC, $exercise['slug'], $level),
                    $exercise['slug'].' level '.$level,
                );
            }
        }
    }

    public function test_paramedic_stroke_scenarios_include_fast_findings(): void
    {
        $scenario = PlatformExercise::scenario(CertificationLevel::PARAMEDIC, 'stroke-neurology', 0, 1);

        $this->assertNotNull($scenario);
        $this->assertArrayHasKey('fast', $scenario);
    }
}
