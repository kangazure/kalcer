<?php

namespace App\Services\Public;

use App\Events\NewContactSubmitted;
use App\Models\Contact;
use App\Models\CoverageArea;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Business logic untuk form kontak publik dan fitur cek jangkauan
 * (coverage check) berbasis perhitungan jarak Haversine ke titik POP/
 * coverage area terdekat.
 */
class ContactService
{
    public function submit(array $data, Request $request): Contact
    {
        $contact = Contact::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'subject' => $data['subject'] ?? null,
            'message' => $data['message'],
            'source' => $data['source'] ?? 'contact_form',
            'address' => $data['address'] ?? null,
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'status' => 'new',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        event(new NewContactSubmitted($contact));

        return $contact;
    }

    public function allCoverageAreasForMap(): Collection
    {
        return CoverageArea::query()
            ->where('is_active', true)
            ->orderBy('region_name')
            ->get();
    }

    /**
     * Cek apakah titik koordinat (lat,lng) berada dalam radius coverage
     * area manapun, menggunakan formula Haversine untuk hitung jarak.
     */
    public function checkCoverage(float $lat, float $lng, ?string $address, Request $request): array
    {
        $areas = CoverageArea::query()->where('is_active', true)->get();

        $nearest = null;
        $nearestDistance = null;
        $isCovered = false;

        foreach ($areas as $area) {
            $distanceMeters = $this->haversineDistance(
                $lat, $lng,
                (float) $area->center_latitude, (float) $area->center_longitude
            );

            if ($nearestDistance === null || $distanceMeters < $nearestDistance) {
                $nearestDistance = $distanceMeters;
                $nearest = $area;
            }

            if ($distanceMeters <= $area->radius_meters && $area->coverage_status !== 'planned') {
                $isCovered = true;
            }
        }

        // Catat sebagai lead di tabel contact jika lokasi belum terjangkau,
        // supaya tim marketing bisa follow-up untuk rencana ekspansi area.
        if (! $isCovered) {
            Contact::query()->create([
                'name' => 'Cek Jangkauan (Belum Terjangkau)',
                'email' => 'noreply@ptjts.id',
                'message' => "Permintaan cek jangkauan dari lokasi belum terjangkau.\nAlamat: {$address}\nKoordinat: {$lat}, {$lng}",
                'source' => 'coverage_check',
                'address' => $address,
                'latitude' => $lat,
                'longitude' => $lng,
                'status' => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }

        return [
            'is_covered' => $isCovered,
            'nearest_area' => $nearest ? [
                'id' => $nearest->id,
                'region_name' => $nearest->region_name,
                'coverage_status' => $nearest->coverage_status,
                'distance_meters' => round($nearestDistance),
            ] : null,
        ];
    }

    protected function haversineDistance(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadiusMeters = 6371000;

        $latDelta = deg2rad($lat2 - $lat1);
        $lngDelta = deg2rad($lng2 - $lng1);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lngDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusMeters * $c;
    }
}
