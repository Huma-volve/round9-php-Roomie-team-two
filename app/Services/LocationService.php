<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class LocationService
{
    /**
     * Calculate distance between two points using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth radius in kilometers

        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);

        $a = sin($latDelta/2) * sin($latDelta/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta/2) * sin($lonDelta/2);

        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earthRadius * $c;
    }

    /**
     * Find nearest properties based on coordinates and radius
     */
    public function findNearestProperties($latitude, $longitude, $radiusKm = 10, $limit = 20)
    {
        // Get properties with coordinates
        $properties = Property::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->where('status', 'available')
            ->get(['id', 'latitude', 'longitude', 'title', 'description']);

        $nearestProperties = [];

        foreach ($properties as $property) {
            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $property->latitude,
                $property->longitude
            );

            if ($distance <= $radiusKm) {
                $property->distance_km = round($distance, 2);
                $nearestProperties[] = $property;
            }
        }

        // Sort by distance
        usort($nearestProperties, function($a, $b) {
            return $a->distance_km <=> $b->distance_km;
        });

        return array_slice($nearestProperties, 0, $limit);
    }

    /**
     * Find nearest rooms based on coordinates and radius
     */
    public function findNearestRooms($latitude, $longitude, $radiusKm = 10, $limit = 20)
    {
        $rooms = Room::select([
                'rooms.id',
                'rooms.property_id',
                'rooms.room_number',
                'rooms.room_type',
                'rooms.price_per_night',
                'rooms.capacity',
                'rooms.room_bed_type',
                'properties.latitude',
                'properties.longitude',
                'properties.title',
                'properties.description'
            ])
            ->join('properties', 'rooms.property_id', '=', 'properties.id')
            ->whereNotNull('properties.latitude')
            ->whereNotNull('properties.longitude')
            ->where('rooms.status', 'available')
            ->where('properties.status', 'available')
            ->get();

        $nearestRooms = [];

        foreach ($rooms as $room) {
            $distance = $this->calculateDistance(
                $latitude,
                $longitude,
                $room->latitude,
                $room->longitude
            );

            if ($distance <= $radiusKm) {
                $room->distance_km = round($distance, 2);
                $nearestRooms[] = $room;
            }
        }

        // Sort by distance
        usort($nearestRooms, function($a, $b) {
            return $a->distance_km <=> $b->distance_km;
        });

        return array_slice($nearestRooms, 0, $limit);
    }

    /**
     * Get properties within bounding box (more efficient for large datasets)
     */
    public function getPropertiesInBoundingBox($latitude, $longitude, $radiusKm = 10)
    {
        $earthRadius = 6371;

        // Calculate bounding box
        $latDelta = ($radiusKm / $earthRadius) * (180 / M_PI);
        $lonDelta = ($radiusKm / $earthRadius) * (180 / M_PI) / cos(deg2rad($latitude));

        $minLat = $latitude - $latDelta;
        $maxLat = $latitude + $latDelta;
        $minLon = $longitude - $lonDelta;
        $maxLon = $longitude + $lonDelta;

        return Property::whereBetween('latitude', [$minLat, $maxLat])
            ->whereBetween('longitude', [$minLon, $maxLon])
            ->where('status', 'available')
            ->get();
    }

    /**
     * Reverse geocode coordinates to get location name
     * This would typically integrate with a geocoding API like Google Maps
     */
    public function reverseGeocode($latitude, $longitude)
    {
        // This is a placeholder for reverse geocoding
        // In a real implementation, you would call a geocoding service
        // like Google Maps Geocoding API, OpenStreetMap Nominatim, etc.

        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'address' => 'Address not available', // Would be populated by API
            'locality' => 'Unknown', // Would be populated by API
            'city' => 'Unknown', // Would be populated by API
            'country' => 'Unknown', // Would be populated by API
        ];
    }

    /**
     * Get location from IP address using ip-api.com
     */
    public function getLocationFromIP($ipAddress = null)
    {
        try {
            $ip = $ipAddress ?? request()->ip();

            // Skip for local/private IPs
            if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
                return [
                    'success' => false,
                    'message' => 'Local/private IP address',
                    'latitude' => null,
                    'longitude' => null,
                    'city' => null,
                    'country' => null
                ];
            }

            $url = "http://ip-api.com/json/{$ip}";
            $response = file_get_contents($url);

            if ($response === false) {
                return [
                    'success' => false,
                    'message' => 'Failed to fetch location data',
                    'latitude' => null,
                    'longitude' => null,
                    'city' => null,
                    'country' => null
                ];
            }

            $data = json_decode($response, true);

            if ($data['status'] !== 'success') {
                return [
                    'success' => false,
                    'message' => $data['message'] ?? 'Location not found',
                    'latitude' => null,
                    'longitude' => null,
                    'city' => null,
                    'country' => null
                ];
            }

            return [
                'success' => true,
                'latitude' => $data['lat'],
                'longitude' => $data['lon'],
                'city' => $data['city'],
                'country' => $data['country'],
                'region' => $data['regionName'],
                'ip' => $ip
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error fetching location: ' . $e->getMessage(),
                'latitude' => null,
                'longitude' => null,
                'city' => null,
                'country' => null
            ];
        }
    }

    /**
     * Update user location based on IP
     */
    public function updateUserLocationFromIP($userId)
    {
        $user = \App\Models\User::find($userId);
        if (!$user) {
            return ['success' => false, 'message' => 'User not found'];
        }

        $locationData = $this->getLocationFromIP();

        if ($locationData['success']) {
            $user->update([
                'latitude' => $locationData['latitude'],
                'longitude' => $locationData['longitude'],
                'city' => $locationData['city'],
                'country' => $locationData['country'],
                'last_location_update' => now(),
            ]);

            return [
                'success' => true,
                'message' => 'Location updated successfully',
                'location' => $locationData
            ];
        }

        return $locationData;
    }

    /**
     * Geocode location name to coordinates
     * This would typically integrate with a geocoding API
     */
    public function geocodeLocation($locationName)
    {
        // This is a placeholder for geocoding
        // In a real implementation, you would call a geocoding service

        return [
            'location' => $locationName,
            'latitude' => null, // Would be populated by API
            'longitude' => null, // Would be populated by API
            'formatted_address' => 'Location not found', // Would be populated by API
        ];
    }
}
