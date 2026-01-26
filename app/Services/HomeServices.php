<?php

namespace App\Services;

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

            $rooms = Room::join('properties', 'rooms.property_id', '=', 'properties.id')
                ->leftJoin('property_images', function ($join) {
                    $join->on('property_images.property_id', '=', 'properties.id')
                        ->where('property_images.is_main', true);
                })
                ->selectRaw("
                rooms.id,
                rooms.room_number,
                rooms.room_type,
                rooms.price_per_month,
                rooms.room_bed_type,
                rooms.capacity,
                properties.title,
                properties.description,
                properties.gender_preference,
                properties.latitude,
                properties.longitude,
                property_images.image_path,
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

            return $rooms;
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
            $rooms = Room::join('properties', 'rooms.property_id', '=', 'properties.id')
                ->leftJoin('property_images', function ($join) {
                    $join->on('property_images.property_id', '=', 'properties.id')
                        ->where('property_images.is_main', true);
                })
                ->select(
                    'rooms.id',
                    'rooms.room_number',
                    'rooms.room_type',
                    'rooms.price_per_month',
                    'rooms.capacity',
                    'rooms.room_bed_type',
                    'properties.title',
                    'properties.description',
                    'properties.gender_preference',
                    'property_images.image_path'
                )
                ->latest('rooms.created_at')
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

        return response()->json([
            'message' => 'Reviews fetched successfully',
            'reviews' => [
                'current_page' => $reviews->currentPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
                'data' => $reviews->items(),
            ]
        ], 200);
    }
}
