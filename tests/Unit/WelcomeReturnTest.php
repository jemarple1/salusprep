<?php

namespace Tests\Unit;

use App\Support\WelcomeReturn;
use Tests\TestCase;

class WelcomeReturnTest extends TestCase
{
    public function test_url_appends_from_welcome_query_param(): void
    {
        $this->assertSame(
            '/emt-basic/skills/airway?from=welcome',
            WelcomeReturn::url('/emt-basic/skills/airway'),
        );

        $this->assertSame(
            '/emt-basic/dashboard?foo=bar&from=welcome',
            WelcomeReturn::url('/emt-basic/dashboard?foo=bar'),
        );
    }
}
