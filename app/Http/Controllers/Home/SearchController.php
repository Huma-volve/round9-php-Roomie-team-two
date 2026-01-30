<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchRequest;
use App\Services\HomeService\SearchServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    private $searchServices;
    public function __construct(SearchServices $searchServices)
    {
        $this->searchServices = $searchServices;
    }
    public function search(SearchRequest $request)
    {
        try {
            $result = $this->searchServices->search($request);

            return response()->json([
                'message' => 'Search completed successfully',
                'data' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                    'items' => $result->items(),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while searching.'
            ], 500);
        }
    }

    /**
     * Get user's search history
     */
    public function getSearchHistory(Request $request)
    {
        try {
            $userId = Auth::id();
            $limit = $request->limit ?? 10;

            $history = $this->searchServices->getSearchHistory($userId, $limit);

            return response()->json([
                'message' => 'Search history retrieved successfully',
                'data' => $history,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Search History Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while retrieving search history.'
            ], 500);
        }
    }

    /**
     * Find nearest properties and rooms based on location
     */
    public function findNearest(SearchRequest $request)
    {
        try {
            $result = $this->searchServices->findNearest($request);

            return response()->json([
                'message' => 'Nearest properties and rooms found successfully',
                'data' => $result,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Find Nearest Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while finding nearest properties.'
            ], 500);
        }
    }

    /**
     * Update user location from IP
     */
    public function updateLocation(Request $request)
    {
        try {
            $user = Auth::user();
            $locationService = app(\App\Services\LocationService::class);

            $result = $locationService->updateUserLocationFromIP($user->id);

            return response()->json([
                'message' => $result['success'] ? 'Location updated successfully' : 'Failed to update location',
                'data' => $result,
            ], $result['success'] ? 200 : 400);
        } catch (\Exception $e) {
            Log::error("Update Location Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while updating location.'
            ], 500);
        }
    }

    /**
     * Get popular search locations
     */
    public function getPopularLocations(Request $request)
    {
        try {
            $limit = $request->limit ?? 10;

            $locations = $this->searchServices->getPopularLocations($limit);

            return response()->json([
                'message' => 'Popular locations retrieved successfully',
                'data' => $locations,
            ], 200);
        } catch (\Exception $e) {
            Log::error("Popular Locations Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while retrieving popular locations.'
            ], 500);
        }
    }
}
