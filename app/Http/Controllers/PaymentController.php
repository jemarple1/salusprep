<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\Payment;
use App\Services\AdaptiveExamService;
use App\Services\StripeCheckoutService;
use App\Support\CertificationLevel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PaymentController extends Controller
{
    public function __construct(
        private AdaptiveExamService $examService,
        private StripeCheckoutService $stripe,
    ) {}

    public function checkoutSection(Request $request): RedirectResponse
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
            productName: CertificationLevel::label($level).' — Unlimited Quizzes',
            productDescription: 'One-time unlock for unlimited adaptive NREMT practice.',
            successUrl: route('platform.home', $slug).'?checkout=success&session_id={CHECKOUT_SESSION_ID}',
            cancelUrl: route('platform.home', $slug).'?checkout=cancelled',
            metadata: [
                'user_id' => (string) $user->id,
                'certification_level' => $level,
            ],
        );

        Payment::create([
            'user_id' => $user->id,
            'certification_level' => $level,
            'amount_cents' => CertificationLevel::PRICE_CENTS,
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
        abort_unless($session->requiresPayment(), 403);

        $slug = $request->attributes->get('section_slug');

        if (! $this->stripe->isConfigured()) {
            return $this->mockExamCheckout($request, $session, $slug);
        }

        $checkoutSession = $this->stripe->createCheckoutSession(
            user: $request->user(),
            productName: CertificationLevel::label($session->certification_level).' — Unlimited Quizzes',
            productDescription: 'One-time unlock for unlimited adaptive NREMT practice.',
            successUrl: route('payment.success', [$slug, $session]).'?checkout=success&session_id={CHECKOUT_SESSION_ID}',
            cancelUrl: route('exam.paywall', [$slug, $session]).'?checkout=cancelled',
            metadata: [
                'user_id' => (string) $request->user()->id,
                'exam_session_id' => (string) $session->id,
                'certification_level' => $session->certification_level,
            ],
        );

        Payment::create([
            'user_id' => $request->user()->id,
            'exam_session_id' => $session->id,
            'certification_level' => $session->certification_level,
            'amount_cents' => CertificationLevel::PRICE_CENTS,
            'status' => Payment::STATUS_PENDING,
            'provider' => 'stripe',
            'stripe_checkout_session_id' => $checkoutSession->id,
        ]);

        return redirect()->away($checkoutSession->url);
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
            ->route('exam.paywall', [$section, $session])
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
            'amount_cents' => CertificationLevel::PRICE_CENTS,
            'status' => Payment::STATUS_COMPLETED,
            'provider' => 'mock',
            'reference' => 'mock_'.uniqid(),
            'paid_at' => now(),
        ]);

        $this->examService->unlockSection($user, $level);

        return redirect()
            ->route('platform.home', $slug)
            ->with('success', 'Unlimited quizzes unlocked (mock mode — add STRIPE_SECRET to use Stripe).');
    }

    private function mockExamCheckout(Request $request, ExamSession $session, string $slug): RedirectResponse
    {
        Payment::create([
            'user_id' => $request->user()->id,
            'exam_session_id' => $session->id,
            'certification_level' => $session->certification_level,
            'amount_cents' => CertificationLevel::PRICE_CENTS,
            'status' => Payment::STATUS_COMPLETED,
            'provider' => 'mock',
            'reference' => 'mock_'.uniqid(),
            'paid_at' => now(),
        ]);

        $this->examService->unlockSection($request->user(), $session->certification_level);

        return redirect()
            ->route('exam.show', [$slug, $session])
            ->with('success', 'Payment successful (mock mode — add STRIPE_SECRET to use Stripe).');
    }
}
