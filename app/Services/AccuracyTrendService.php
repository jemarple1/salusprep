<?php

namespace App\Services;

use App\Models\ExamSession;
use App\Models\User;

class AccuracyTrendService
{
    /** @return array{points: list<array{quiz_number: int, accuracy_percent: int, questions_answered: int, label: string, date: string}>, trend: string, trend_delta: int, trend_message: string} */
    public function forUser(User $user, string $certificationLevel): array
    {
        $quizNumbers = ExamSession::quizNumbersForUser($user->id, $certificationLevel);

        $sessions = ExamSession::query()
            ->where('user_id', $user->id)
            ->where('certification_level', $certificationLevel)
            ->where('questions_answered', '>', 0)
            ->orderBy('created_at')
            ->orderBy('id')
            ->get();

        $points = $sessions->map(function (ExamSession $session) use ($quizNumbers) {
            $quizNumber = $quizNumbers[$session->id] ?? 0;

            return [
                'quiz_number' => $quizNumber,
                'accuracy_percent' => $session->scorePercent(),
                'questions_answered' => $session->questions_answered,
                'label' => 'Quiz #'.$quizNumber,
                'date' => ($session->completed_at ?? $session->created_at)->format('M j'),
            ];
        })->values()->all();

        [$trend, $delta, $message] = $this->analyzeTrend($points);

        $displayPoints = count($points) > 15 ? array_slice($points, -15) : $points;

        return [
            'points' => $displayPoints,
            'trend' => $trend,
            'trend_delta' => $delta,
            'trend_message' => $message,
            'total_quizzes' => count($points),
        ];
    }

    /**
     * @param  list<array{accuracy_percent: int}>  $points
     * @return array{0: string, 1: int, 2: string}
     */
    private function analyzeTrend(array $points): array
    {
        $count = count($points);

        if ($count < 2) {
            return ['insufficient', 0, 'Complete at least 2 quizzes to see your accuracy trend.'];
        }

        if ($count === 2) {
            $delta = $points[1]['accuracy_percent'] - $points[0]['accuracy_percent'];

            return [$this->trendFromDelta($delta), $delta, $this->messageFromTrend($this->trendFromDelta($delta), $delta)];
        }

        $early = array_slice($points, 0, (int) ceil($count / 2));
        $recent = array_slice($points, (int) floor($count / 2));

        $earlyAvg = (int) round(collect($early)->avg('accuracy_percent'));
        $recentAvg = (int) round(collect($recent)->avg('accuracy_percent'));
        $delta = $recentAvg - $earlyAvg;

        return [$this->trendFromDelta($delta), $delta, $this->messageFromTrend($this->trendFromDelta($delta), $delta)];
    }

    private function trendFromDelta(int $delta): string
    {
        if ($delta >= 5) {
            return 'improving';
        }

        if ($delta <= -5) {
            return 'declining';
        }

        return 'stable';
    }

    private function messageFromTrend(string $trend, int $delta): string
    {
        $abs = abs($delta);

        return match ($trend) {
            'improving' => "Your recent quizzes are {$abs} points higher on average — keep it up!",
            'declining' => "Recent accuracy is down {$abs} points — try flashcard review on weak categories.",
            default => 'Your accuracy is holding steady — push for consistency with study mode.',
        };
    }
}
