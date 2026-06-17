# Stripe setup for SalusPrep

## 1. Get API keys

1. Sign in at [https://dashboard.stripe.com](https://dashboard.stripe.com) (create an account if needed).
2. Turn on **Test mode** (toggle in the dashboard header).
3. Open **Developers → API keys**.
4. Copy:
   - **Publishable key** → `STRIPE_KEY`
   - **Secret key** → `STRIPE_SECRET`

## 2. Add keys to `.env`

```env
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
STRIPE_WEBHOOK_SECRET=your_webhook_signing_secret
```

Then clear config cache:

```bash
php artisan config:clear
php artisan stripe:verify
```

`stripe:verify` should print **Connected to Stripe successfully.**

## 3. Local testing (Herd)

Payments use **Stripe Checkout** — you’ll be redirected to Stripe’s hosted page.

1. Use test card: `4242 4242 4242 4242`
2. Any future expiry, any CVC, any ZIP
3. After payment, Stripe redirects back and unlocks the section automatically

**Webhooks (optional locally)** — unlock also works via the success redirect. For webhook testing:

```bash
# Install Stripe CLI: https://stripe.com/docs/stripe-cli
stripe login
stripe listen --forward-to http://example-app.test/stripe/webhook
```

Copy the `whsec_...` secret from the CLI output into `STRIPE_WEBHOOK_SECRET`.

## 4. Production

1. Switch Stripe dashboard to **Live mode**.
2. Use live keys (`pk_live_`, `sk_live_`) in your hosting env vars.
3. Set `APP_URL` to your real domain (required for Checkout redirects).
4. In **Developers → Webhooks**, add endpoint:
   ```
   https://your-domain.com/stripe/webhook
   ```
   Event: `checkout.session.completed`
5. Put the signing secret in `STRIPE_WEBHOOK_SECRET`.

## Pricing

Each section unlock is **$8.99 USD** (899 cents), configured in `App\Support\CertificationLevel::PRICE_CENTS`.

## Troubleshooting

| Issue | Fix |
|-------|-----|
| Still using mock checkout | Set `STRIPE_SECRET` and run `php artisan config:clear` |
| Paid but not unlocked | Refresh the page; check `storage/logs/laravel.log` |
| Webhook 400 | Wrong `STRIPE_WEBHOOK_SECRET` for this endpoint |
| Redirect error | `APP_URL` must match your site URL exactly |
