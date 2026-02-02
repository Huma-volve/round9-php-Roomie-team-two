<?php

namespace App\Services\HomeService;

use App\Models\Property;
use App\Models\Room;
use App\Models\SearchHistory;
use App\Services\LocationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchServices
{
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Enhanced search method with location support and search history
     */
    public function search(Request $request)
    {
        $query = Room::query()
            ->select([
                'rooms.id',
                'rooms.property_id',
                'rooms.room_number',
                'rooms.room_type',
                'rooms.price_per_night',
                'rooms.capacity',
                'rooms.room_bed_type',
                'rooms.num_beds',
                'rooms.size_in_sq_m',
                'rooms.deposit',
                'rooms.minimum_stay',
                'rooms.created_at',
            ])
            ->with([
                'property:id,title,description,gender_preference,rent_type,num_rooms,latitude,longitude',
                'property.mainImage:id,property_id,image_path',
                'roomImages:id,room_id,image_path'
            ])
            ->join('properties', 'rooms.property_id', '=', 'properties.id')
            ->where('properties.status', 'available')
            ->where('rooms.status', 'available');

        // 1) Property Type
        if ($request->property_type) {
            $query->where('properties.rent_type', $request->property_type);
        }

        // 2) BHK (only applicable for apartments)
        if ($request->bhk) {
            $query->where('properties.num_rooms', $request->bhk);
        }

        // 3) Budget
        if ($request->min_budget) {
            $query->where('rooms.price_per_night', '>=', $request->min_budget);
        }

        if ($request->max_budget) {
            $query->where('rooms.price_per_night', '<=', $request->max_budget);
        }

        // 4) Locality
        if ($request->locality) {
            $query->where(function ($q) use ($request) {
                $q->where('properties.title', 'LIKE', "%{$request->locality}%")
                  ->orWhere('properties.description', 'LIKE', "%{$request->locality}%");
            });
        }

        // 5) Location-based search (nearest properties)
        if ($request->latitude && $request->longitude) {
            $radius = $request->radius_km ?? 10;

            // Use location service to filter by distance
            $query->whereRaw("
                (6371 * acos(cos(radians(?)) * cos(radians(properties.latitude)) *
                cos(radians(properties.longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(properties.latitude)))) <= ?
            ", [$request->latitude, $request->longitude, $request->latitude, $radius]);
        }

        $perPage = $request->per_page ?? 20;
        $data = $query->paginate($perPage);

        // Save search history if user is authenticated
        if (Auth::check()) {
            $this->saveSearchHistory($request, $data->total());
        }

        return $data;
    }

    /**
     * Save search history for authenticated user
     */
    private function saveSearchHistory(Request $request, $resultsCount)
    {
        SearchHistory::create([
            'user_id' => Auth::id(),
            'property_type' => $request->property_type,
            'bhk' => $request->bhk,
            'min_budget' => $request->min_budget,
            'max_budget' => $request->max_budget,
            'locality' => $request->locality,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius_km' => $request->radius_km ?? 10,
            'results_count' => $resultsCount,
        ]);
    }

    /**
     * Get user's search history
     */
    public function getSearchHistory($userId, $limit = 10)
    {
        return SearchHistory::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Find nearest properties/rooms using user's location
     */
    public function findNearest(Request $request)
    {
        $user = auth()->user();

        // If user doesn't have location, try to get it from IP
        if (!$user->latitude || !$user->longitude) {
            $locationResult = $this->locationService->updateUserLocationFromIP($user->id);

            if (!$locationResult['success']) {
                // If IP location fails, show all available properties/rooms as fallback
                return $this->findAllAvailable($request);
            }
        }

        $latitude = $user->latitude;
        $longitude = $user->longitude;
        $radius = $request->radius_km ?? 50; // Increased default radius
        $limit = $request->limit ?? 20;

        $properties = $this->locationService->findNearestProperties(
            $latitude,
            $longitude,
            $radius,
            $limit
        );

        $rooms = $this->locationService->findNearestRooms(
            $latitude,
            $longitude,
            $radius,
            $limit
        );

        // If no results found nearby, expand search or show fallback
        if (empty($properties) && empty($rooms)) {
            return $this->findAllAvailable($request, 'No properties found near your location. Showing all available options.');
        }

        // Save search history
        $searchRequest = new Request([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'radius_km' => $radius
        ]);
        $this->saveSearchHistory($searchRequest, count($properties) + count($rooms));

        return [
            'properties' => $properties,
            'rooms' => $rooms,
            'search_location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'city' => $user->city,
                'country' => $user->country,
                'radius_km' => $radius
            ],
            'user_location' => [
                'latitude' => $user->latitude,
                'longitude' => $user->longitude,
                'city' => $user->city,
                'country' => $user->country,
                'last_updated' => $user->last_location_update
            ]
        ];
    }

    /**
     * Fallback method to show all available properties/rooms when location-based search fails
     */
    private function findAllAvailable(Request $request, $message = 'Showing all available options')
    {
        $limit = $request->limit ?? 20;

        $properties = Property::where('status', 'available')
            ->with('mainImage')
            ->limit($limit)
            ->get(['id', 'title', 'description', 'price_per_night']);

        $rooms = Room::where('status', 'available')
            ->with(['property', 'roomImages'])
            ->limit($limit)
            ->get(['id', 'property_id', 'room_number', 'room_type', 'price_per_night']);

        return [
            'properties' => $properties,
            'rooms' => $rooms,
            'message' => $message,
            'search_location' => [
                'type' => 'fallback',
                'radius_km' => null
            ],
            'user_location' => null
        ];
    }

    /**
     * Get popular search locations based on search history
     */
    public function getPopularLocations($limit = 10)
    {
        return SearchHistory::select('locality')
            ->whereNotNull('locality')
            ->where('locality', '!=', '')
            ->groupBy('locality')
            ->orderByRaw('COUNT(*) DESC')
            ->limit($limit)
            ->pluck('locality');
    }
}
