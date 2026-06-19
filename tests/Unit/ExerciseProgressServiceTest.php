<?php

namespace Tests\Unit;

use App\Models\ExerciseScenarioCompletion;
use App\Models\User;
use App\Services\ExerciseProgressService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class ExerciseProgressServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_marks_scenario_complete_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $service = app(ExerciseProgressService::class);
        $service->markComplete($request, CertificationLevel::EMT_BASIC, 'burn-scoring', 2);

        $this->assertTrue($service->isComplete($request, CertificationLevel::EMT_BASIC, 'burn-scoring', 2));
        $this->assertSame([2], $service->completedIndexes($request, CertificationLevel::EMT_BASIC, 'burn-scoring'));
    }

    public function test_first_incomplete_index_skips_completed_scenarios(): void
    {
        $user = User::factory()->create();
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $service = app(ExerciseProgressService::class);
        $service->markComplete($request, CertificationLevel::EMT_BASIC, 'gcs-scenarios', 0);
        $service->markComplete($request, CertificationLevel::EMT_BASIC, 'gcs-scenarios', 1);

        $this->assertSame(2, $service->firstIncompleteIndex($request, CertificationLevel::EMT_BASIC, 'gcs-scenarios'));
    }

    public function test_merge_guest_progress_into_user(): void
    {
        $user = User::factory()->create();
        $guestToken = '550e8400-e29b-41d4-a716-446655440000';

        ExerciseScenarioCompletion::create([
            'guest_token' => $guestToken,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exercise_slug' => 'stroke-scale',
            'exercise_level' => 1,
            'scenario_index' => 4,
            'completed_at' => now(),
        ]);

        app(ExerciseProgressService::class)->mergeGuestIntoUser($guestToken, $user);

        $this->assertDatabaseHas('exercise_scenario_completions', [
            'user_id' => $user->id,
            'exercise_slug' => 'stroke-scale',
            'scenario_index' => 4,
        ]);

        $this->assertDatabaseMissing('exercise_scenario_completions', [
            'guest_token' => $guestToken,
        ]);
    }

    public function test_completing_all_scenarios_unlocks_next_level(): void
    {
        $user = User::factory()->create();
        $request = Request::create('/');
        $request->setUserResolver(fn () => $user);

        $service = app(ExerciseProgressService::class);

        for ($index = 0; $index < ExerciseProgressService::SCENARIOS_PER_EXERCISE; $index++) {
            $service->markComplete($request, CertificationLevel::EMT_BASIC, 'soap-charting', $index, 1);
        }

        $this->assertTrue($service->isLevelComplete($request, CertificationLevel::EMT_BASIC, 'soap-charting', 1));
        $this->assertSame(2, $service->maxUnlockedLevel($request, CertificationLevel::EMT_BASIC, 'soap-charting'));
    }
}
