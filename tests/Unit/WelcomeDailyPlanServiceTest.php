<?php

namespace Tests\Unit;

use App\Models\ExamSession;
use App\Models\ExerciseScenarioCompletion;
use App\Models\SectionAccess;
use App\Models\User;
use App\Services\AdaptiveExamService;
use App\Services\WelcomeDailyPlanService;
use App\Support\CertificationLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class WelcomeDailyPlanServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_daily_plan_is_available_from_unlock_day(): void
    {
        Carbon::setTestNow('2026-06-10 12:00:00');

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        $service = app(WelcomeDailyPlanService::class);

        $this->assertTrue($service->showDailyPlan($access, $user));
        $this->assertTrue($service->isFirstDay($access, $user));

        Carbon::setTestNow('2026-06-11 09:00:00');

        $this->assertTrue($service->showDailyPlan($access->fresh(), $user));
        $this->assertFalse($service->isFirstDay($access->fresh(), $user));

        Carbon::setTestNow();
    }

    public function test_checklist_tracks_skills_quizzes_and_mock_exam(): void
    {
        Carbon::setTestNow('2026-06-11 10:00:00');

        $user = User::factory()->create();
        app(AdaptiveExamService::class)->unlockSection($user, CertificationLevel::EMT_BASIC);

        SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->update(['unlocked_at' => now()->subDay()]);

        $recommended = app(WelcomeDailyPlanService::class)
            ->recommendedSkills(CertificationLevel::EMT_BASIC, 1);
        $skillSlug = $recommended[0]['slug'];

        ExerciseScenarioCompletion::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exercise_slug' => $skillSlug,
            'exercise_level' => 1,
            'scenario_index' => 0,
            'completed_at' => now(),
        ]);

        ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'current_difficulty' => 3,
            'questions_answered' => 25,
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
        ]);

        ExamSession::query()->create([
            'user_id' => $user->id,
            'certification_level' => CertificationLevel::EMT_BASIC,
            'exam_type' => ExamSession::TYPE_MOCK,
            'current_difficulty' => 3,
            'questions_answered' => 80,
            'status' => ExamSession::STATUS_COMPLETED,
            'completed_at' => now(),
            'mock_outcome' => ExamSession::MOCK_PASS,
        ]);

        $request = Request::create('/emt-basic/welcome', 'GET');
        $request->setUserResolver(fn () => $user);

        $access = SectionAccess::query()
            ->where('user_id', $user->id)
            ->where('certification_level', CertificationLevel::EMT_BASIC)
            ->first();

        $plan = app(WelcomeDailyPlanService::class)->forUser($request, $user, CertificationLevel::EMT_BASIC, $access, null);

        $this->assertTrue($plan['showDailyPlan']);
        $this->assertSame(6, $plan['totalCount']);
        $this->assertGreaterThanOrEqual(2, $plan['completedCount']);

        $completedKeys = collect($plan['items'])
            ->filter(fn (array $item) => $item['completed'])
            ->pluck('key')
            ->all();

        $this->assertContains('skill:'.$skillSlug, $completedKeys);
        $this->assertContains('mock:1', $completedKeys);

        Carbon::setTestNow();
    }
}
