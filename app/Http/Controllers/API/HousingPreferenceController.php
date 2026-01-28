<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HousingPreference;
use App\Http\Requests\StoreHousingPreferenceRequest;
use App\Http\Resources\Profile\HousingPreferenceResource;
use App\Http\Requests\UpdateHousingPreferenceRequest;
use Illuminate\Support\Facades\Auth;

class HousingPreferenceController extends Controller
{
    /**
     * Get all housing preferences for authenticated user
     */
    public function index()
    {
        $preferences = Auth::user()->housingPreferences;

        return response()->json([
            'success' => true,
            'data' => $preferences
        ], 200);
    }

    /**
     * Get single housing preference
     */
    public function show($id)
    {
        $preference = HousingPreference::where('user_id', Auth::id())
            ->find($id);

        if (!$preference) {
            return response()->json([
                'success' => false,
                'message' => 'Housing preference not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $preference
        ], 200);
    }

    /**
     * Create new housing preference
     */
    public function store(StoreHousingPreferenceRequest $request)
    {
        $preference = HousingPreference::create([
            'user_id' => Auth::id(),
            'preferred_location' => $request->preferred_location,
            'move_in_date' => $request->move_in_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Housing preference created successfully',
            'data' => $preference
        ], 201);
    }

    /**
     * Update housing preference
     */
public function update(UpdateHousingPreferenceRequest $request, $id)
{
    $preference = HousingPreference::where('user_id', Auth::id())
        ->findOrFail($id);

    $preference->preferred_location = $request->preferred_location;
    $preference->move_in_date = $request->move_in_date;
    $preference->save();

    return response()->json([
        'success' => true,
        'message' => 'Housing preference updated successfully',
        'data' => $preference
    ], 200);
}

    /**
     * Delete housing preference
     */
    public function destroy($id)
    {
        $preference = HousingPreference::where('user_id', Auth::id())
            ->find($id);

        if (!$preference) {
            return response()->json([
                'success' => false,
                'message' => 'Housing preference not found'
            ], 404);
        }

        $preference->delete();

        return response()->json([
            'success' => true,
            'message' => 'Housing preference deleted successfully'
        ], 200);
    }
}