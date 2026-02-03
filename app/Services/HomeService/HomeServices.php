<?php

namespace App\Services\HomeService;

use App\Models\Property;
use App\Models\Review;
use App\Models\Room;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class HomeServices
{
    public function getUserLocation()
    {
        try {
            $ip = request()->ip();
            // $ip = '8.8.8.8'; // Test IP 
            $location = Location::get($ip);
            if (!$location) {
                return null;
            }

            return [$location->latitude, $location->longitude];
        } catch (\Exception $e) {
            Log::error("Error getting user location: " . $e->getMessage());
            return null;
        }
    }

    public function getNearbyRooms()
    {
        try {
            $location = $this->getUserLocation();

            if (!$location) {
                return response()->json([
                    'message' => 'Not Found Rooms & Homes Near You'
                ], 404);
            }

            [$lat, $lng] = $location;

            $rooms = Room::with([
                'property:id,title,description,gender_preference,latitude,longitude',
                'property.mainImage:id,property_id,image_path'
            ])
                ->join('properties', 'rooms.property_id', '=', 'properties.id')
                ->selectRaw("
                     rooms.*,
                      (6371 * 1000 * acos(
                        cos(radians(?)) *
                          cos(radians(properties.latitude)) *
                       cos(radians(properties.longitude) - radians(?)) +
                        sin(radians(?)) *
                    sin(radians(properties.latitude))
                     )) AS distance
                       ", [$lat, $lng, $lat])
                ->having('distance', '<=', 10000)
                ->orderBy('distance')
                ->paginate(6);
            return response()->json([
                'message' => 'Rooms data fetched successfully',
                'total' => $rooms->total(),
                'per_page' => $rooms->perPage(),
                'current_page' => $rooms->currentPage(),
                'rooms_near_you' => $rooms->items()
            ]);
        } catch (\Exception $e) {
            Log::error("Error fetching nearby rooms: " . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while fetching nearby rooms'
            ], 500);
        }
    }

    public function latestRooms()
    {
        try {
            $rooms = Room::with([
                'property:id,title,description,gender_preference',
                'property.mainImage:id,property_id,image_path'
            ])
                ->select(
                    'id',
                    'property_id',
                    'room_number',
                    'room_type',
                    'price_per_night',
                    'capacity',
                    'room_bed_type',
                    'created_at'
                )
                ->latest()
                ->paginate(10);

            return $rooms;
        } catch (\Exception $e) {
            Log::error("Error fetching latest rooms: " . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while fetching latest rooms'
            ], 500);
        }
    }
    public function getReviews()
    {
        $reviews = Review::with(['property:id,title', 'user:id,name'])
            ->where('rating', '>=', 3)
            ->paginate(5);

        return $reviews;
    }

    public function getPropertiesCount()
    {
        return Property::count();
    }
}
