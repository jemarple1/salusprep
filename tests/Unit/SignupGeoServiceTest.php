<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\SignupGeoService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SignupGeoServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_point_uses_exact_coordinates_when_available(): void
    {
        $user = User::factory()->create([
            'signup_latitude' => 40.7128,
            'signup_longitude' => -74.0060,
            'signup_country_name' => 'United States',
        ]);

        $point = app(SignupGeoService::class)->mapPointForUser($user);

        $this->assertSame(40.7128, $point['lat']);
        $this->assertSame(-74.0060, $point['lon']);
        $this->assertSame('United States', $point['country']);
    }

    public function test_map_point_falls_back_to_country_centroid(): void
    {
        $user = User::factory()->create([
            'signup_country_code' => 'US',
            'signup_country_name' => 'United States',
            'signup_latitude' => null,
            'signup_longitude' => null,
        ]);

        $point = app(SignupGeoService::class)->mapPointForUser($user);

        $this->assertNotNull($point);
        $this->assertSame(39.8283, $point['lat']);
        $this->assertSame(-98.5795, $point['lon']);
    }

    public function test_map_point_returns_null_without_geo_data(): void
    {
        $user = User::factory()->create([
            'signup_country_code' => null,
            'signup_latitude' => null,
            'signup_longitude' => null,
        ]);

        $this->assertNull(app(SignupGeoService::class)->mapPointForUser($user));
    }
}
