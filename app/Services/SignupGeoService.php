<?php

namespace App\Services;

use App\Models\GuestDevice;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class SignupGeoService
{
    /** @return array{signup_country_code: ?string, signup_country_name: ?string, signup_latitude: ?float, signup_longitude: ?float} */
    public function fromRequest(Request $request): array
    {
        $geo = $this->geoFromRequest($request);

        return [
            'signup_country_code' => $geo['country_code'],
            'signup_country_name' => $geo['country_name'],
            'signup_latitude' => $geo['latitude'],
            'signup_longitude' => $geo['longitude'],
        ];
    }

    /** @return array{country_code: ?string, country_name: ?string, latitude: ?float, longitude: ?float, ip: ?string} */
    public function geoFromRequest(Request $request): array
    {
        $empty = [
            'country_code' => null,
            'country_name' => null,
            'latitude' => null,
            'longitude' => null,
            'ip' => null,
        ];

        $ip = (string) $request->ip();
        $empty['ip'] = $ip !== '' ? $ip : null;

        $countryCode = strtoupper((string) ($request->header('CF-IPCountry') ?? $request->server('HTTP_CF_IPCOUNTRY') ?? ''));

        if ($countryCode !== '' && $countryCode !== 'XX' && strlen($countryCode) === 2) {
            [$lat, $lon] = $this->countryCentroid($countryCode);

            return [
                'country_code' => $countryCode,
                'country_name' => $this->countryName($countryCode),
                'latitude' => $lat,
                'longitude' => $lon,
                'ip' => $empty['ip'],
            ];
        }

        if ($ip === '' || $this->isPrivateIp($ip)) {
            return $empty;
        }

        $lookup = Cache::remember("signup-geo:{$ip}", now()->addDay(), function () use ($ip) {
            try {
                $response = Http::timeout(3)
                    ->get("http://ip-api.com/json/{$ip}", [
                        'fields' => 'status,country,countryCode,lat,lon',
                    ]);

                if (! $response->successful()) {
                    return null;
                }

                $data = $response->json();

                if (($data['status'] ?? '') !== 'success') {
                    return null;
                }

                return [
                    'country_code' => strtoupper((string) ($data['countryCode'] ?? '')),
                    'country_name' => (string) ($data['country'] ?? ''),
                    'latitude' => (float) ($data['lat'] ?? 0),
                    'longitude' => (float) ($data['lon'] ?? 0),
                ];
            } catch (\Throwable) {
                return null;
            }
        });

        if ($lookup === null || $lookup['country_code'] === '') {
            return $empty;
        }

        return [
            'country_code' => $lookup['country_code'],
            'country_name' => $lookup['country_name'] !== '' ? $lookup['country_name'] : $this->countryName($lookup['country_code']),
            'latitude' => $lookup['latitude'],
            'longitude' => $lookup['longitude'],
            'ip' => $empty['ip'],
        ];
    }

    /** @return array{lat: float, lon: float, country: ?string}|null */
    public function mapPointForGuestDevice(GuestDevice $device): ?array
    {
        if ($device->latitude !== null && $device->longitude !== null) {
            return [
                'lat' => (float) $device->latitude,
                'lon' => (float) $device->longitude,
                'country' => $device->country_name,
            ];
        }

        $countryCode = strtoupper((string) ($device->country_code ?? ''));

        if ($countryCode === '') {
            return null;
        }

        [$lat, $lon] = $this->countryCentroid($countryCode);

        return [
            'lat' => $lat,
            'lon' => $lon,
            'country' => $device->country_name ?: $this->countryName($countryCode),
        ];
    }

    /** @return array{lat: float, lon: float, country: ?string}|null */
    public function mapPointForUser(User $user): ?array
    {
        if ($user->signup_latitude !== null && $user->signup_longitude !== null) {
            return [
                'lat' => (float) $user->signup_latitude,
                'lon' => (float) $user->signup_longitude,
                'country' => $user->signup_country_name,
            ];
        }

        $countryCode = strtoupper((string) ($user->signup_country_code ?? ''));

        if ($countryCode === '') {
            return null;
        }

        [$lat, $lon] = $this->countryCentroid($countryCode);

        return [
            'lat' => $lat,
            'lon' => $lon,
            'country' => $user->signup_country_name ?: $this->countryName($countryCode),
        ];
    }

    private function isPrivateIp(string $ip): bool
    {
        return ! filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE,
        );
    }

    /** @return array{0: float, 1: float} */
    public function countryCentroid(string $countryCode): array
    {
        $centroids = [
            'US' => [39.8283, -98.5795],
            'CA' => [56.1304, -106.3468],
            'MX' => [23.6345, -102.5528],
            'GB' => [55.3781, -3.4360],
            'IE' => [53.4129, -8.2439],
            'FR' => [46.2276, 2.2137],
            'DE' => [51.1657, 10.4515],
            'ES' => [40.4637, -3.7492],
            'IT' => [41.8719, 12.5674],
            'NL' => [52.1326, 5.2913],
            'BE' => [50.5039, 4.4699],
            'SE' => [60.1282, 18.6435],
            'NO' => [60.4720, 8.4689],
            'DK' => [56.2639, 9.5018],
            'FI' => [61.9241, 25.7482],
            'PL' => [51.9194, 19.1451],
            'CH' => [46.8182, 8.2275],
            'AT' => [47.5162, 14.5501],
            'PT' => [39.3999, -8.2245],
            'AU' => [-25.2744, 133.7751],
            'NZ' => [-40.9006, 174.8860],
            'JP' => [36.2048, 138.2529],
            'KR' => [35.9078, 127.7669],
            'CN' => [35.8617, 104.1954],
            'IN' => [20.5937, 78.9629],
            'BR' => [-14.2350, -51.9253],
            'AR' => [-38.4161, -63.6167],
            'ZA' => [-30.5595, 22.9375],
            'NG' => [9.0820, 8.6753],
            'EG' => [26.8206, 30.8025],
            'IL' => [31.0461, 34.8516],
            'AE' => [23.4241, 53.8478],
            'SA' => [23.8859, 45.0792],
            'PH' => [12.8797, 121.7740],
            'SG' => [1.3521, 103.8198],
        ];

        return $centroids[$countryCode] ?? [20.0, 0.0];
    }

    private function countryName(string $countryCode): string
    {
        $names = [
            'US' => 'United States',
            'CA' => 'Canada',
            'MX' => 'Mexico',
            'GB' => 'United Kingdom',
            'IE' => 'Ireland',
            'FR' => 'France',
            'DE' => 'Germany',
            'ES' => 'Spain',
            'IT' => 'Italy',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'PT' => 'Portugal',
            'AU' => 'Australia',
            'NZ' => 'New Zealand',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'CN' => 'China',
            'IN' => 'India',
            'BR' => 'Brazil',
            'AR' => 'Argentina',
            'ZA' => 'South Africa',
            'NG' => 'Nigeria',
            'EG' => 'Egypt',
            'IL' => 'Israel',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'PH' => 'Philippines',
            'SG' => 'Singapore',
        ];

        return $names[$countryCode] ?? $countryCode;
    }
}
