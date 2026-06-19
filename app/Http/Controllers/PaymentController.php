<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Payment;
use App\Services\AdaptiveExamService;
use App\Services\PreviewAccessService;
use App\Services\StripeCheckoutService;
use App\Support\CertificationLevel;
use App\Support\SectionPricing;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(
        private AdaptiveExamService $examService,
        private StripeCheckoutService $stripe,
        private PreviewAccessService $preview,
    ) {}

    public function checkoutSection(Request $request): RedirectResponse
    {
        return $this->beginSectionCheckout($request);
    }

    public function startSectionCheckout(Request $request): RedirectResponse
    {
        return $this->beginSectionCheckout($request);
    }

    private function beginSectionCheckout(Request $request): RedirectResponse
    {
        $level = $request->attributes->get('certification_level');
        $slug = $request->attributes->get('section_slug');
        $user = $request->user();

        if ($user->hasSectionAccess($level)) {
            return redirect()
                ->route('platform.home', $slug)
                ->with('success', CertificationLevel::label($level).' is already unlocked.');
        }

        if (! $this->stripe->isConfigured()) {
            return $this->mockSectionCheckout($user, $level, $slug);
        }

        $checkoutSession = $this->stripe->createCheckoutSession(
            user: $user,
            productName: CertificationLevel::label($level).' — Full Access',
            productDescription: CertificationLevel::unlockProductDescription($level),
            successUrl: route('platform.welcome', $slug).'?session_id={CHECKOUT_SESSION_ID}',
            cancelUrl: route('platform.paywall', $slug).'?checkout=cancelled',
            metadata: [
                'user_id' => (string) $user->id,
                'certification_level' => $level,
            ],
        );

        Payment::create([
            'user_id' => $user->id,
            'certification_level' => $level,
            'amount_cents' => SectionPricing::priceCents(),
            'status' => Payment::STATUS_PENDING,
            'provider' => 'stripe',
            'stripe_checkout_session_id' => $checkoutSession->id,
        ]);

        return redirect()->away($checkoutSession->url);
    }

    public function checkout(Request $request, string $section, ExamSession $session): RedirectResponse
    {
        abort_unless($session->user_id === $request->user()->id, 403);
        abort_unless($session->certification_level === $request->attributes->get('certification_level'), 403);

        return redirect()->route('platform.paywall', $section);
    }

    public function success(Request $request, string $section, ExamSession $session): RedirectResponse
    {
        abort_unless($session->user_id === $request->user()->id, 403);
        abort_unless($session->certification_level === $request->attributes->get('certification_level'), 403);

        $this->fulfillFromRequest($request);

        if ($session->fresh()->sectionIsUnlocked()) {
            return redirect()
                ->route('exam.show', [$section, $session])
                ->with('success', 'Payment successful! Continue your quiz.');
        }

        return redirect()
            ->route('platform.paywall', $section)
            ->with('success', 'Payment received. Unlocking your section…');
    }

    public function webhook(Request $request): Response
    {
        if (! $this->stripe->isConfigured()) {
            return response('Stripe not configured.', 503);
        }

        $result = $this->stripe->constructWebhookEvent(
            $request->getContent(),
            $request->header('Stripe-Signature', ''),
        );

        if (! $result['ok']) {
            Log::warning('Stripe webhook rejected.', ['message' => $result['message'] ?? 'Unknown error']);

            return response($result['message'] ?? 'Invalid webhook.', 400);
        }

        $event = $result['event'];

        if ($event->type === 'checkout.session.completed') {
            /** @var \Stripe\Checkout\Session $checkoutSession */
            $checkoutSession = $event->data->object;
            $this->stripe->fulfillCheckoutSession($checkoutSession);
        }

        return response('Webhook handled.', 200);
    }

    private function fulfillFromRequest(Request $request): void
    {
        $sessionId = $request->query('session_id');

        if (! is_string($sessionId) || $sessionId === '') {
            return;
        }

        $this->stripe->fulfillFromCheckoutSessionId($sessionId);
    }

    private function mockSectionCheckout($user, string $level, string $slug): RedirectResponse
    {
        Payment::create([
            'user_id' => $user->id,
            'certification_level' => $level,
            'amount_cents' => SectionPricing::priceCents(),
            'status' => Payment::STATUS_COMPLETED,
            'provider' => 'mock',
            'reference' => 'mock_'.uniqid(),
            'paid_at' => now(),
        ]);

        $this->examService->unlockSection($user, $level);

        return redirect()
            ->route('platform.welcome', $slug)
            ->with([
                'success' => 'Full Access unlocked (mock mode — add STRIPE_SECRET to use Stripe).',
                'track_purchase_conversion' => true,
            ]);
    }
}
