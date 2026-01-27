<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LifestyleTrait;
use App\Http\Requests\StoreLifestyleTraitRequest;
use Illuminate\Support\Facades\Auth;

class LifestyleTraitController extends Controller
{
    /**
     * Get lifestyle trait for authenticated user
     */
    public function show()
    {
        $trait = Auth::user()->lifestyleTrait;

        if (!$trait) {
            return response()->json([
                'success' => false,
                'message' => 'Lifestyle trait not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $trait
        ], 200);
    }

    /**
     * Create or update lifestyle trait
     */
    public function createOrUpdate(StoreLifestyleTraitRequest $request)
    {
        $trait = LifestyleTrait::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'traits' => $request->traits,
                'early_bird' => $request->early_bird,
                'smoker' => $request->smoker,
                'pets' => $request->pets,
                'work_from_home' => $request->work_from_home,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Lifestyle trait saved successfully',
            'data' => $trait
        ], 200);
    }

    /**
     * Delete lifestyle trait
     */
    public function destroy()
    {
        $trait = Auth::user()->lifestyleTrait;

        if (!$trait) {
            return response()->json([
                'success' => false,
                'message' => 'Lifestyle trait not found'
            ], 404);
        }

        $trait->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lifestyle trait deleted successfully'
        ], 200);
    }
}