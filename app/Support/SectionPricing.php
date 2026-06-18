<?php

namespace App\Support;

use Carbon\Carbon;

class SectionPricing
{
    public static function listPriceCents(): int
    {
        return (int) config('pricing.regular_price_cents', CertificationLevel::PRICE_CENTS);
    }

    public static function salePriceCents(): int
    {
        return (int) config('pricing.sale_price_cents', 599);
    }

    public static function isSaleActive(?Carbon $at = null): bool
    {
        $at ??= now();

        $endsAt = Carbon::parse(
            config('pricing.sale_ends_at'),
            config('app.timezone'),
        );

        return $at->lte($endsAt);
    }

    public static function priceCents(?Carbon $at = null): int
    {
        return self::isSaleActive($at)
            ? self::salePriceCents()
            : self::listPriceCents();
    }

    public static function formatted(?Carbon $at = null): string
    {
        return '$'.number_format(self::priceCents($at) / 100, 2);
    }

    public static function stripePromotionCode(): ?string
    {
        if (! self::isSaleActive()) {
            return null;
        }

        $code = config('pricing.stripe_promotion_code');

        return filled($code) ? $code : null;
    }
}
