<?php

namespace App\Console\Commands;

use App\Services\StripeCheckoutService;
use Illuminate\Console\Command;

class StripeVerifyCommand extends Command
{
    protected $signature = 'stripe:verify';

    protected $description = 'Verify Stripe API credentials in .env';

    public function handle(StripeCheckoutService $stripe): int
    {
        $this->line('SalusPrep Stripe configuration');
        $this->newLine();

        $key = config('services.stripe.key');
        $secret = config('services.stripe.secret');
        $webhook = config('services.stripe.webhook_secret');

        $this->table(
            ['Variable', 'Status'],
            [
                ['STRIPE_KEY', $key ? 'set ('.substr($key, 0, 12).'…)' : 'missing'],
                ['STRIPE_SECRET', $secret ? 'set ('.substr($secret, 0, 12).'…)' : 'missing'],
                ['STRIPE_WEBHOOK_SECRET', $webhook ? 'set ('.substr($webhook, 0, 12).'…)' : 'missing (optional for local if using success redirect)'],
            ],
        );

        if (! $stripe->isConfigured()) {
            $this->error('Add STRIPE_KEY and STRIPE_SECRET to .env from https://dashboard.stripe.com/test/apikeys');

            return self::FAILURE;
        }

        $result = $stripe->verifyConnection();

        if ($result['ok']) {
            $this->info($result['message']);
            $this->newLine();
            $this->line('Local webhook forwarding (optional):');
            $this->line('  stripe listen --forward-to '.url('/stripe/webhook'));

            return self::SUCCESS;
        }

        $this->error($result['message']);

        return self::FAILURE;
    }
}
