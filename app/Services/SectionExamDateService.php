<?php

namespace App\Services;

use App\Models\SectionAccess;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SectionExamDateService
{
    public function update(SectionAccess $access, mixed $examDateInput): void
    {
        if ($examDateInput === '' || $examDateInput === null) {
            $access->exam_date = null;
            $access->save();

            return;
        }

        $validated = Validator::make(
            ['exam_date' => $examDateInput],
            [
                'exam_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addYears(2)->toDateString()],
            ],
        )->validate();

        $access->exam_date = $validated['exam_date'];
        $access->save();
    }

    /** @throws ValidationException */
    public function validateInput(mixed $examDateInput): ?string
    {
        if ($examDateInput === '' || $examDateInput === null) {
            return null;
        }

        return Validator::make(
            ['exam_date' => $examDateInput],
            [
                'exam_date' => ['required', 'date', 'after_or_equal:today', 'before_or_equal:'.now()->addYears(2)->toDateString()],
            ],
        )->validate()['exam_date'];
    }
}
