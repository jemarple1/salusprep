<?php

namespace App\Http\Controllers;

use App\Services\ExerciseProgressService;
use App\Services\PreviewAccessService;
use App\Support\BurnRegion;
use App\Support\PlatformExercise;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExerciseController extends Controller
{
    public function __construct(
        private PreviewAccessService $preview,
        private ExerciseProgressService $progress,
    ) {}

    public function index(Request $request): View
    {
        $level = $request->attributes->get('certification_level');

        return view('exercises.index', [
            'exercises' => PlatformExercise::cardsForLevel($level),
        ]);
    }

    public function show(Request $request, string $section, string $exercise): View|RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $hasAccess = $this->preview->hasAccess($request, $level);

        $meta = PlatformExercise::find($level, $exercise);
        abort_if($meta === null, 404);

        $hasExerciseLevels = PlatformExercise::hasExerciseLevels($level, $exercise);
        $maxUnlockedLevel = $hasExerciseLevels
            ? $this->progress->maxUnlockedLevel($request, $level, $exercise)
            : 1;

        if (! $request->has('scenario')) {
            $resume = $this->progress->resumeTarget($request, $level, $exercise);

            if ($resume['scenario'] !== 0 || $resume['exercise_level'] !== 1) {
                return redirect()->route('exercises.show', array_filter([
                    'section' => $section,
                    'exercise' => $exercise,
                    'scenario' => $resume['scenario'],
                    'level' => $resume['exercise_level'] > 1 ? $resume['exercise_level'] : null,
                ]));
            }
        }

        $exerciseLevel = $hasExerciseLevels
            ? min(max(1, (int) $request->query('level', 1)), $maxUnlockedLevel)
            : 1;

        $scenarioIndex = max(0, (int) $request->query('scenario', 0));
        $scenario = PlatformExercise::scenario($level, $exercise, $scenarioIndex, $exerciseLevel);
        abort_if($scenario === null, 404);

        $completedIndexes = $this->progress->completedIndexes($request, $level, $exercise, $exerciseLevel);
        $scenarioLinks = PlatformExercise::scenarioLinks($level, $exercise, $hasAccess, $completedIndexes, $exerciseLevel);
        $scenarioCount = count(PlatformExercise::scenarios($level, $exercise, $exerciseLevel));
        $completedLevels = $hasExerciseLevels
            ? $this->progress->completedLevels($request, $level, $exercise)
            : [];
        $levelLinks = $hasExerciseLevels
            ? PlatformExercise::levelLinks($level, $exercise, $hasAccess, $completedLevels, $maxUnlockedLevel, $exerciseLevel)
            : [];

        $view = match ($meta['ui'] ?? $meta['type']) {
            'soap' => 'exercises.soap',
            'triage-tags' => 'exercises.triage-tags',
            'burn-map' => 'exercises.burn-map',
            'gcs-picker' => 'exercises.gcs-picker',
            'stroke-fast' => 'exercises.stroke-fast',
            'vitals-panel' => 'exercises.vitals-panel',
            'triage-priority' => 'exercises.triage-priority',
            'salt-triage' => 'exercises.salt-triage',
            'pharma-yesno' => 'exercises.pharma-yesno',
            'pharma-choice' => 'exercises.pharma-choice',
            'scenario' => 'exercises.scenario',
            default => abort(404),
        };

        return view($view, [
            'exercise' => $meta,
            'scenario' => $scenario,
            'scenarioIndex' => $scenarioIndex,
            'scenarioLinks' => $scenarioLinks,
            'scenarioCount' => $scenarioCount,
            'completedCount' => count($completedIndexes),
            'exerciseLevel' => $exerciseLevel,
            'levelLinks' => $levelLinks,
            'maxUnlockedLevel' => $maxUnlockedLevel,
            'completedLevels' => $completedLevels,
        ]);
    }

    public function check(Request $request, string $section, string $exercise): JsonResponse
    {
        $level = $request->attributes->get('certification_level');

        $meta = PlatformExercise::find($level, $exercise);
        abort_if($meta === null, 404);

        $validated = $request->validate([
            'scenario' => ['required', 'integer', 'min:0'],
            'level' => ['sometimes', 'integer', 'min:1'],
        ]);

        $scenarioIndex = (int) $validated['scenario'];
        $exerciseLevel = (int) ($validated['level'] ?? 1);
        $scenario = PlatformExercise::scenario($level, $exercise, $scenarioIndex, $exerciseLevel);
        abort_if($scenario === null, 404);

        $ui = $meta['ui'] ?? $meta['type'];

        $response = match ($ui) {
            'soap' => $this->checkSoap($request, $scenario),
            'burn-map' => $this->checkBurnMap($request, $scenario),
            'gcs-picker' => $this->checkGcs($request, $scenario),
            default => $this->checkSimple($request, $scenario, $ui),
        };

        $data = $response->getData(true);

        if ($data['correct'] ?? false) {
            $this->progress->markComplete($request, $level, $exercise, $scenarioIndex, $exerciseLevel);

            $nextIndex = $this->progress->nextIncompleteIndex($request, $level, $exercise, $scenarioIndex, $exerciseLevel);

            if ($nextIndex !== null) {
                $data['next_scenario_url'] = route('exercises.show', array_filter([
                    'section' => $section,
                    'exercise' => $exercise,
                    'scenario' => $nextIndex,
                    'level' => $exerciseLevel > 1 ? $exerciseLevel : null,
                ]));
            } elseif (
                PlatformExercise::hasExerciseLevels($level, $exercise)
                && $this->progress->isLevelComplete($request, $level, $exercise, $exerciseLevel)
                && $exerciseLevel < PlatformExercise::exerciseLevelCount($level, $exercise)
            ) {
                $data['level_complete'] = true;
                $data['next_scenario_url'] = route('exercises.show', [
                    'section' => $section,
                    'exercise' => $exercise,
                    'level' => $exerciseLevel + 1,
                    'scenario' => 0,
                ]);
            }
        }

        $limitReached = $this->preview->recordAction($request, $level);

        if ($limitReached) {
            $data['paywall_url'] = route('platform.paywall', $section);
        }

        return response()->json($data);
    }

    /** @param  array<string, mixed>  $scenario */
    private function checkSimple(Request $request, array $scenario, string $ui): JsonResponse
    {
        $answer = $request->validate([
            'answer' => ['required', 'string'],
        ])['answer'];

        $correct = match ($ui) {
            'triage-tags', 'salt-triage', 'triage-priority', 'stroke-fast', 'vitals-panel', 'pharma-yesno', 'pharma-choice', 'scenario' => $answer === $scenario['correct'],
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
            'placements.*' => ['required', 'string', 'in:S,O,A,P,X'],
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

        $chosenRegions = BurnRegion::normalizeKeys($validated['regions']);
        $expectedRegions = BurnRegion::normalizeKeys($scenario['correct_regions'] ?? []);
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
