<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlashcardReview extends Model
{
    public const RESPONSE_WEAK = 'weak';

    public const RESPONSE_STRONG = 'strong';

    protected $fillable = [
        'user_id',
        'question_id',
        'certification_level',
        'ease_score',
        'times_weak',
        'times_strong',
        'last_reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'last_reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
