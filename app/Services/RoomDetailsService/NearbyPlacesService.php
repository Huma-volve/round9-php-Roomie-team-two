<?php

namespace   App\Services\RoomDetailsService;

use App\Models\Room;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NearbyPlacesService
{
    protected array $types = [
        'commute' => [
            'highway' => 'bus_stop',
            'railway' => 'station',
            'amenity' => 'bus_station',
        ],
        'schools' => [
            'amenity' => 'school',
        ],
        'supermarkets' => [
            'shop' => 'supermarket',
        ],
        'parks' => [
            'leisure' => 'park',
            // 'leisure2' => 'garden',
        ],
        'clinics' => [
            'amenity' => 'clinic',
            // 'amenity2' => 'doctors',
            // 'amenity3' => 'hospital',
            // 'amenity4' => 'pharmacy',
        ],
    ];
    public function getNearbyPlaces($room): array
    {
        $radius = 500;
        $results = [];

        $lat = $room->property->latitude;
        $lng = $room->property->longitude;
        foreach ($this->types as $type => $tags) {
            $cacheKey = "nearby_{$type}_{$lat}_{$lng}";
            $results[$type] = cache()->remember($cacheKey, 3600, function () use ($lat, $lng, $tags, $radius) {
                $attempts = 0;
                do {
                    try {
                        return $this->queryOverpass($lat, $lng, $tags, $radius);
                    } catch (\Exception $e) {
                        $attempts++;
                        sleep(1); 
                    }
                } while ($attempts < 3);

                return []; 
            });
        }

        return $results;
    }

    protected function queryOverpass($lat, $lng, array $filters, int $radius): array
    {
        $queries = [];

        foreach ($filters as $key => $value) {
            $queries[] = "node[$key=$value](around:$radius,$lat,$lng);";
            $queries[] = "way[$key=$value](around:$radius,$lat,$lng);";
        }

        $query = "
            [out:json];
             (
               " . implode("\n", $queries) . "
             );
             out center tags;
              ";

        $response = Http::timeout(30)
            ->retry(2, 1000)
            ->asForm()
            ->post('https://overpass-api.de/api/interpreter', [
                'data' => $query
            ]);

        if (!$response->successful()) {
            Log::error('Overpass failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'query' => $query,
            ]);
            return [];
        }

        return $response->json()['elements'] ?? [];
    }
}
