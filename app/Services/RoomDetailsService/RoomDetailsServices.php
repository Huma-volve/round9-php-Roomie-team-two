<?php

namespace App\Services\RoomDetailsService;

use App\Models\Review;
use App\Models\Room;
use App\Models\RoomImage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Stevebauman\Location\Facades\Location;

class RoomDetailsServices
{
    public function retrieveAllImagesForRoomByID($id)
    {
        return RoomImage::where('room_id', $id)
            ->select('room_id', 'image_path')
            ->get();
    }

    public function calculateDistanceForRoom($room)
    {
        try {
            // 1. Get user location
            // $ip =request()->ip();
            $ip =  '8.8.8.8'; // IP Test 
            $location = Location::get($ip);

            if (!$location) {
                return null;
            }

            $userLat = $location->latitude;
            $userLon = $location->longitude;


            if (!$room->property || !$room->property->latitude || !$room->property->longitude) {
                return null;
            }
            $earthRadius = 6371;

            $latDelta = deg2rad($room->property->latitude - $userLat);
            $lonDelta = deg2rad($room->property->longitude - $userLon);

            $angle = 2 * asin(
                sqrt(
                    pow(sin($latDelta / 2), 2) +
                        cos(deg2rad($userLat)) * cos(deg2rad($room->property->latitude)) *
                        pow(sin($lonDelta / 2), 2)
                )
            );

            $distance = round($earthRadius * $angle, 2);

            return $room->distance = $distance;
        } catch (\Exception $e) {
            Log::error("Single room distance error: " . $e->getMessage());
            return null;
        }
    }

    public function getReviews($room)
    {
        $reviews = Review::where('property_id', $room->property_id)->get();
        return  $reviews;
    }

    public function getSimilarRooms($room)
    {
        $similarRooms = Room::with('roomImages')
            ->where('id', '!=', $room->id)
            ->where('num_beds', $room->num_beds)
            ->where('price_per_night', $room->price_per_night)
            ->where('status', 'available')
            ->limit(6)
            ->get();

        return $similarRooms;
    }

    public function getAccountAdmin()
    {
        return User::where('is_admin', true)->get();
    }
}
