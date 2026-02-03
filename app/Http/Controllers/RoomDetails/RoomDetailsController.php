<?php

namespace App\Http\Controllers\RoomDetails;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\RoomDetailsService\NearbyPlacesService;
use App\Services\RoomDetailsService\RoomDetailsServices;
use App\Services\RoomDetailsService\RoomMortgageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RoomDetailsController extends Controller
{
    private $roomDetailsServices ,$nearbyPlacesService ,$roomMortgageService;

    public function __construct(RoomDetailsServices $roomDetailsServices , NearbyPlacesService $nearbyPlacesService, RoomMortgageService $roomMortgageService)
    {
        $this->roomDetailsServices = $roomDetailsServices;
        $this->nearbyPlacesService = $nearbyPlacesService;
        $this->roomMortgageService = $roomMortgageService;
    }

    public function getAllRoomDetails($id)
    {
        if(!$id){
            return null;
        }
        try {

            $room = Room::with('property')->findOrFail($id);


            //  Room description
            $roomDescription = $room->property->description;

            //  Room details + overview
            [$roomDetails ,$roomOverview] = $room;

            // reviews
            $reviews = $this->roomDetailsServices->getReviews($room);

            //  Room images
            $roomImages = $this->roomDetailsServices->retrieveAllImagesForRoomByID($id);

            //  Amenities & house rules
            $amenities = $room->room_amenities;

            //  Similar rooms
            $similarRooms = $this->roomDetailsServices->getSimilarRooms($room);

            //  Distance 
            $distance = $this->roomDetailsServices->calculateDistanceForRoom($room);

            //  Mortgage breakdown
            $roomMortgage = $this->roomMortgageService->getRoomMortgage($id);

            // Near By Places
             $nearbyPlaces = $this->nearbyPlacesService->getNearbyPlaces($room);

            $admin = $this->roomDetailsServices->getAccountAdmin();


            $response = [
                'message' => 'Room details fetched successfully',
                'description' => $roomDescription,
                'Room details And overview' => $roomDetails,
                'reviews' => $reviews,
                'images' => $roomImages,
                'amenities' => $amenities,
                'similar_rooms' => $similarRooms,
                'distance_km' => $distance,
                'mortgage' => $roomMortgage,
                'nearbyPlaces' => $nearbyPlaces,
                'admin' => $admin,
            ];

            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error("Error fetching room details: " . $e->getMessage());

            return response()->json([
                'message' => 'Failed to fetch room details',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
