<?php

namespace App\Models;

use App\Support\CertificationLevel;
use Illuminate\Database\Eloquent\Model;

class GuestSectionProgress extends Model
{
    protected $table = 'guest_section_progress';

    protected $fillable = [
        'guest_token',
        'certification_level',
        'free_questions_used',
    ];

    public function freeQuestionsRemaining(): int
    {
        return max(0, CertificationLevel::FREE_QUESTIONS - $this->free_questions_used);
    }

    public function requiresPayment(): bool
    {
        return $this->free_questions_used >= CertificationLevel::FREE_QUESTIONS;
    }
}
