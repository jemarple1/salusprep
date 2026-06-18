<?php

namespace App\Http\Controllers;

use App\Support\PlatformExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function show(Request $request, string $section, string $exercise): View
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $unlocked = $user !== null && $user->hasSectionAccess($level);

        $meta = PlatformExercise::find($level, $exercise);
        abort_if($meta === null, 404);

        $scenarioIndex = max(0, (int) $request->query('scenario', 0));
        $scenario = PlatformExercise::scenario($level, $exercise, $scenarioIndex);
        abort_if($scenario === null, 404);

        $scenarioLinks = PlatformExercise::scenarioLinks($level, $exercise, $user, $unlocked);

        if (! PlatformExercise::canAccessScenario($user, $level, $unlocked, $scenarioIndex)) {
            return view('exercises.scenario-paywall', [
                'exercise' => $meta,
                'scenario' => $scenario,
                'scenarioIndex' => $scenarioIndex,
                'scenarioLinks' => $scenarioLinks,
                'requiresAuth' => $user === null,
                'unlocked' => $unlocked,
            ]);
        }

        $view = match ($meta['ui'] ?? $meta['type']) {
            'soap' => 'exercises.soap',
            'triage-tags' => 'exercises.triage-tags',
            'burn-map' => 'exercises.burn-map',
            'gcs-picker' => 'exercises.gcs-picker',
            'stroke-fast' => 'exercises.stroke-fast',
            'vitals-panel' => 'exercises.vitals-panel',
            'triage-priority' => 'exercises.triage-priority',
            'salt-triage' => 'exercises.salt-triage',
            'scenario' => 'exercises.scenario',
            default => abort(404),
        };

        return view($view, [
            'exercise' => $meta,
            'scenario' => $scenario,
            'scenarioIndex' => $scenarioIndex,
            'scenarioLinks' => $scenarioLinks,
            'unlocked' => $unlocked,
        ]);
    }

    public function check(Request $request, string $section, string $exercise): JsonResponse
    {
        $level = $request->attributes->get('certification_level');
        $user = $request->user();
        $unlocked = $user !== null && $user->hasSectionAccess($level);

        $meta = PlatformExercise::find($level, $exercise);
        abort_if($meta === null, 404);

        $validated = $request->validate([
            'scenario' => ['required', 'integer', 'min:0'],
        ]);

        abort_unless(
            PlatformExercise::canAccessScenario($user, $level, $unlocked, $validated['scenario']),
            403,
        );

        $scenario = PlatformExercise::scenario($level, $exercise, $validated['scenario']);
        abort_if($scenario === null, 404);

        $ui = $meta['ui'] ?? $meta['type'];

        if ($ui === 'soap') {
            return $this->checkSoap($request, $scenario);
        }

        if ($ui === 'burn-map') {
            return $this->checkBurnMap($request, $scenario);
        }

        if ($ui === 'gcs-picker') {
            return $this->checkGcs($request, $scenario);
        }

        $answer = $request->validate([
            'answer' => ['required', 'string'],
        ])['answer'];

        $correct = match ($ui) {
            'triage-tags', 'salt-triage', 'triage-priority', 'stroke-fast', 'vitals-panel', 'scenario' => $answer === $scenario['correct'],
            default => false,
        };

        return response()->json([
            'correct' => $correct,
            'explanation' => $scenario['explanation'],
            'correct_key' => $scenario['correct'],
        ]);
    }

    /** @param  array<string, mixed>  $scenario */
    private function checkSoap(Request $request, array $scenario): JsonResponse
    {
        $placements = $request->validate([
            'placements' => ['required', 'array'],
            'placements.*' => ['required', 'string', 'in:S,O,A,P'],
        ])['placements'];

        $correctMap = collect($scenario['sentences'])->mapWithKeys(
            fn (array $sentence) => [$sentence['id'] => $sentence['section']],
        );

        $results = [];
        $correctCount = 0;

        foreach ($correctMap as $id => $expected) {
            $chosen = $placements[$id] ?? null;
            $isCorrect = $chosen === $expected;
            $results[$id] = ['correct' => $isCorrect, 'expected' => $expected];
            if ($isCorrect) {
                $correctCount++;
            }
        }

        $total = $correctMap->count();

        return response()->json([
            'correct' => $correctCount === $total,
            'score' => $correctCount,
            'total' => $total,
            'results' => $results,
        ]);
    }

    /** @param  array<string, mixed>  $scenario */
    private function checkBurnMap(Request $request, array $scenario): JsonResponse
    {
        $validated = $request->validate([
            'regions' => ['required', 'array'],
            'regions.*' => ['required', 'string'],
            'answer' => ['required', 'string'],
        ]);

        $chosenRegions = collect($validated['regions'])->sort()->values()->all();
        $expectedRegions = collect($scenario['correct_regions'])->sort()->values()->all();
        $regionsCorrect = $chosenRegions === $expectedRegions;
        $percentCorrect = $validated['answer'] === ($scenario['correct_percent'] ?? '');

        $correct = $regionsCorrect && $percentCorrect;

        $explanation = $scenario['explanation'];
        if (! $regionsCorrect && ! $percentCorrect) {
            $explanation = 'Both the burned areas and TBSA percentage need correction. '.$explanation;
        } elseif (! $regionsCorrect) {
            $explanation = 'The TBSA is right, but recheck the burned areas on the diagram. '.$explanation;
        } elseif (! $percentCorrect) {
            $explanation = 'The burned areas are right, but recheck the TBSA percentage. '.$explanation;
        }

        return response()->json([
            'correct' => $correct,
            'explanation' => $explanation,
            'expected' => $expectedRegions,
        ]);
    }

    /** @param  array<string, mixed>  $scenario */
    private function checkGcs(Request $request, array $scenario): JsonResponse
    {
        $scores = $request->validate([
            'eye' => ['required', 'integer', 'min:1', 'max:4'],
            'verbal' => ['required', 'integer', 'min:1', 'max:5'],
            'motor' => ['required', 'integer', 'min:1', 'max:6'],
        ]);

        $correct = $scores['eye'] === $scenario['eye']
            && $scores['verbal'] === $scenario['verbal']
            && $scores['motor'] === $scenario['motor'];

        $total = $scores['eye'] + $scores['verbal'] + $scores['motor'];
        $expectedTotal = $scenario['eye'] + $scenario['verbal'] + $scenario['motor'];

        return response()->json([
            'correct' => $correct,
            'explanation' => $scenario['explanation'].' Your total: GCS '.$total.'. Expected: GCS '.$expectedTotal.'.',
            'total' => $total,
        ]);
    }
}
