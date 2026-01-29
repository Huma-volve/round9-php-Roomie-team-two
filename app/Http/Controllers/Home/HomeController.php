<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
 use App\Services\HomeService\HomeServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    private $homeServices;

    public function __construct(HomeServices $homeServices)
    {
        $this->homeServices = $homeServices;
    }

    public function index()
    {
        try {
            $nearby = $this->homeServices->getNearbyRooms();
            $latest = $this->homeServices->latestRooms();
            $reviews = $this->homeServices->getReviews();
            $propertiesCount = $this->homeServices->getPropertiesCount();

            return response()->json([
                'message' => 'Rooms data fetched successfully',
                'property_count'=> $propertiesCount,
                'rooms_near_you' => [
                    'current_page' => $nearby->currentPage(),
                    'per_page' => $nearby->perPage(),
                    'total' => $nearby->total(),
                    'data' => $nearby->items(),
                ],
                'latest_rooms' => [
                    'current_page' => $latest->currentPage(),
                    'per_page' => $latest->perPage(),
                    'total' => $latest->total(),
                    'data' => $latest->items(),
                ],
                'reviews' => [
                    'current_page' => $reviews->currentPage(),
                    'per_page' => $reviews->perPage(),
                    'total' => $reviews->total(),
                    'data' => $reviews->items(),
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error("Error fetching rooms: " . $e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while fetching rooms.'
            ], 500);
        }
    }
}
