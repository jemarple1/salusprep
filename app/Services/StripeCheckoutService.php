<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use App\Support\SectionPricing;
use App\Support\CertificationLevel;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeCheckoutSession;
use Stripe\Exception\ApiErrorException;
use Stripe\PromotionCode;
use Stripe\Stripe;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeCheckoutService
{
    public function __construct(private AdaptiveExamService $examService) {}

    public function isConfigured(): bool
    {
        return filled(config('services.stripe.secret'));
    }

    public function verifyConnection(): array
    {
        if (! $this->isConfigured()) {
            return ['ok' => false, 'message' => 'STRIPE_SECRET is not set in .env'];
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            StripeCheckoutSession::all(['limit' => 1]);

            return ['ok' => true, 'message' => 'Connected to Stripe successfully.'];
        } catch (ApiErrorException $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param  array<string, string>  $metadata
     */
    public function createCheckoutSession(
        User $user,
        string $productName,
        string $productDescription,
        string $successUrl,
        string $cancelUrl,
        array $metadata,
    ): StripeCheckoutSession {
        Stripe::setApiKey(config('services.stripe.secret'));

        $unitAmount = SectionPricing::listPriceCents();
        $sessionParams = [
            'mode' => 'payment',
            'customer_email' => $user->email,
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $unitAmount,
                    'product_data' => [
                        'name' => $productName,
                        'description' => $productDescription,
                    ],
                ],
            ]],
            'metadata' => $metadata,
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ];

        $promotionCode = SectionPricing::stripePromotionCode();
        if ($promotionCode !== null) {
            $promotionCodeId = $this->resolveStripePromotionCodeId($promotionCode);

            if ($promotionCodeId !== null) {
                $sessionParams['discounts'] = [
                    ['promotion_code' => $promotionCodeId],
                ];
            } else {
                $sessionParams['line_items'][0]['price_data']['unit_amount'] = SectionPricing::salePriceCents();

                Log::warning('Stripe promotion code not found; using sale price directly.', [
                    'code' => $promotionCode,
                ]);
            }
        }

        try {
            return StripeCheckoutSession::create($sessionParams);
        } catch (ApiErrorException $exception) {
            if (! isset($sessionParams['discounts'])) {
                throw $exception;
            }

            Log::warning('Stripe rejected promotion code; retrying checkout at sale price.', [
                'code' => $promotionCode,
                'message' => $exception->getMessage(),
            ]);

            unset($sessionParams['discounts']);
            $sessionParams['line_items'][0]['price_data']['unit_amount'] = SectionPricing::salePriceCents();

            return StripeCheckoutSession::create($sessionParams);
        }
    }

    private function resolveStripePromotionCodeId(string $code): ?string
    {
        $configuredId = config('pricing.stripe_promotion_id');
        if (filled($configuredId) && str_starts_with($configuredId, 'promo_')) {
            return $configuredId;
        }

        if (filled($configuredId)) {
            Log::warning('STRIPE_PROMOTION_ID must be a promo_... ID, not the customer-facing code. Looking up by code instead.', [
                'configured' => $configuredId,
            ]);
        }

        try {
            $promotionCodes = PromotionCode::all([
                'code' => $code,
                'active' => true,
                'limit' => 1,
            ]);

            return $promotionCodes->data[0]->id ?? null;
        } catch (ApiErrorException $exception) {
            Log::warning('Stripe promotion code lookup failed.', [
                'code' => $code,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    public function fulfillFromCheckoutSessionId(string $checkoutSessionId): bool
    {
        if (! $this->isConfigured()) {
            return false;
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $checkoutSession = StripeCheckoutSession::retrieve($checkoutSessionId);
        } catch (ApiErrorException $exception) {
            Log::warning('Stripe checkout retrieval failed.', [
                'session_id' => $checkoutSessionId,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }

        if ($checkoutSession->payment_status !== 'paid') {
            return false;
        }

        return $this->fulfillCheckoutSession($checkoutSession);
    }

    public function fulfillCheckoutSession(StripeCheckoutSession $checkoutSession): bool
    {
        $payment = Payment::query()
            ->where('stripe_checkout_session_id', $checkoutSession->id)
            ->first();

        if ($payment === null) {
            Log::warning('Stripe payment record not found for checkout session.', [
                'session_id' => $checkoutSession->id,
            ]);

            return false;
        }

        if ($payment->status === Payment::STATUS_COMPLETED) {
            return true;
        }

        $payment->update([
            'status' => Payment::STATUS_COMPLETED,
            'amount_cents' => (int) ($checkoutSession->amount_total ?? $payment->amount_cents),
            'stripe_payment_intent_id' => is_string($checkoutSession->payment_intent)
                ? $checkoutSession->payment_intent
                : $checkoutSession->payment_intent?->id,
            'paid_at' => now(),
        ]);

        $user = $payment->user;
        $level = $payment->certification_level ?? $checkoutSession->metadata['certification_level'] ?? null;

        if ($user !== null && is_string($level) && $level !== '') {
            $this->examService->unlockSection($user, $level);

            return true;
        }

        return false;
    }

    /**
     * @return array{ok: bool, event?: \Stripe\Event, message?: string}
     */
    public function constructWebhookEvent(string $payload, string $signature): array
    {
        $webhookSecret = config('services.stripe.webhook_secret');

        if (! filled($webhookSecret)) {
            return ['ok' => false, 'message' => 'STRIPE_WEBHOOK_SECRET is not set.'];
        }

        try {
            return [
                'ok' => true,
                'event' => Webhook::constructEvent($payload, $signature, $webhookSecret),
            ];
        } catch (SignatureVerificationException $exception) {
            return ['ok' => false, 'message' => $exception->getMessage()];
        }
    }
}
