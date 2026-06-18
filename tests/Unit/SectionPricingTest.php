<?php

namespace Tests\Unit;

use App\Support\SectionPricing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SectionPricingTest extends TestCase
{
    public function test_sale_price_is_active_before_end_of_june(): void
    {
        Config::set('pricing.sale_ends_at', '2026-06-30 23:59:59');
        Config::set('app.timezone', 'UTC');

        Carbon::setTestNow('2026-06-15 12:00:00');

        $this->assertTrue(SectionPricing::isSaleActive());
        $this->assertSame(599, SectionPricing::priceCents());
        $this->assertSame('$5.99', SectionPricing::formatted());
        $this->assertSame('JunePrep', SectionPricing::stripePromotionCode());
    }

    public function test_regular_price_returns_after_sale_ends(): void
    {
        Config::set('pricing.sale_ends_at', '2026-06-30 23:59:59');
        Config::set('app.timezone', 'UTC');

        Carbon::setTestNow('2026-07-01 00:00:00');

        $this->assertFalse(SectionPricing::isSaleActive());
        $this->assertSame(899, SectionPricing::priceCents());
        $this->assertNull(SectionPricing::stripePromotionCode());
    }
}
