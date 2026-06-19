<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryProficiencyService
{
    /** @return Collection<int, object{category: string, total: int, correct: int, incorrect: int, accuracy_percent: int, miss_percent: int}> */
    public function forUser(User $user, string $certificationLevel): Collection
    {
        return $this->mapCategoryRows(
            DB::table('exam_answers')
                ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
                ->join('questions', 'exam_answers.question_id', '=', 'questions.id')
                ->where('exam_sessions.user_id', $user->id)
                ->where('exam_sessions.certification_level', $certificationLevel)
                ->groupBy('questions.category')
                ->orderBy('questions.category')
                ->select([
                    'questions.category as category',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN exam_answers.is_correct THEN 1 ELSE 0 END) as correct'),
                ])
                ->get(),
        );
    }

    /** @return Collection<int, object{category: string, total: int, correct: int, incorrect: int, accuracy_percent: int, miss_percent: int}> */
    public function forGuest(string $guestToken, string $certificationLevel): Collection
    {
        return $this->mapCategoryRows(
            DB::table('exam_answers')
                ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
                ->join('questions', 'exam_answers.question_id', '=', 'questions.id')
                ->where('exam_sessions.guest_token', $guestToken)
                ->whereNull('exam_sessions.user_id')
                ->where('exam_sessions.certification_level', $certificationLevel)
                ->groupBy('questions.category')
                ->orderBy('questions.category')
                ->select([
                    'questions.category as category',
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN exam_answers.is_correct THEN 1 ELSE 0 END) as correct'),
                ])
                ->get(),
        );
    }

    /** @return Collection<int, object{category: string, total: int, correct: int, incorrect: int, accuracy_percent: int, miss_percent: int}> */
    public function forLearner(Request $request, GuestService $guests, string $certificationLevel): Collection
    {
        $user = $request->user();

        if ($user !== null) {
            return $this->forUser($user, $certificationLevel);
        }

        return $this->forGuest($guests->token($request), $certificationLevel);
    }

    /** @return array{total: int, correct: int, incorrect: int, accuracy_percent: int} */
    public function overall(User $user, string $certificationLevel): array
    {
        $row = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->where('exam_sessions.user_id', $user->id)
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->select([
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN exam_answers.is_correct THEN 1 ELSE 0 END) as correct'),
            ])
            ->first();

        return $this->mapOverallRow($row);
    }

    /** @return array{total: int, correct: int, incorrect: int, accuracy_percent: int} */
    public function overallForGuest(string $guestToken, string $certificationLevel): array
    {
        $row = DB::table('exam_answers')
            ->join('exam_sessions', 'exam_answers.exam_session_id', '=', 'exam_sessions.id')
            ->where('exam_sessions.guest_token', $guestToken)
            ->whereNull('exam_sessions.user_id')
            ->where('exam_sessions.certification_level', $certificationLevel)
            ->select([
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN exam_answers.is_correct THEN 1 ELSE 0 END) as correct'),
            ])
            ->first();

        return $this->mapOverallRow($row);
    }

    /** @return array{total: int, correct: int, incorrect: int, accuracy_percent: int} */
    public function overallForLearner(Request $request, GuestService $guests, string $certificationLevel): array
    {
        $user = $request->user();

        if ($user !== null) {
            return $this->overall($user, $certificationLevel);
        }

        return $this->overallForGuest($guests->token($request), $certificationLevel);
    }

    /** @param  \Illuminate\Support\Collection<int, object>  $rows */
    private function mapCategoryRows($rows): Collection
    {
        return $rows->map(function ($row) {
            $total = (int) $row->total;
            $correct = (int) $row->correct;
            $incorrect = $total - $correct;
            $accuracy = $total > 0 ? (int) round(($correct / $total) * 100) : 0;

            return (object) [
                'category' => $row->category,
                'total' => $total,
                'correct' => $correct,
                'incorrect' => $incorrect,
                'accuracy_percent' => $accuracy,
                'miss_percent' => 100 - $accuracy,
            ];
        });
    }

    /** @return array{total: int, correct: int, incorrect: int, accuracy_percent: int} */
    private function mapOverallRow(?object $row): array
    {
        $total = (int) ($row->total ?? 0);
        $correct = (int) ($row->correct ?? 0);
        $accuracy = $total > 0 ? (int) round(($correct / $total) * 100) : 0;

        return [
            'total' => $total,
            'correct' => $correct,
            'incorrect' => $total - $correct,
            'accuracy_percent' => $accuracy,
        ];
    }
}
