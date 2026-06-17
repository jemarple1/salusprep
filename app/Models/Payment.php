<?php

namespace App\Models;

use App\Support\CertificationLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_COMPLETED = 'completed';

    public const AMOUNT_CENTS = CertificationLevel::PRICE_CENTS;

    protected $fillable = [
        'user_id',
        'exam_session_id',
        'certification_level',
        'amount_cents',
        'status',
        'provider',
        'stripe_checkout_session_id',
        'stripe_payment_intent_id',
        'reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function examSession(): BelongsTo
    {
        return $this->belongsTo(ExamSession::class);
    }

    public function formattedAmount(): string
    {
        return '$'.number_format($this->amount_cents / 100, 2);
    }

    public function sectionLabel(): string
    {
        return CertificationLevel::label($this->certification_level ?? '');
    }
}
