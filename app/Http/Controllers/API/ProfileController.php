<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerification;
use App\Http\Requests\UpdateBasicInfoRequest;
use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Get authenticated user profile
     */
    public function show()
    {
        $user = Auth::user()->load([
            'housingPreferences',
            'verification',
            'lifestyleTrait'
        ]);

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'address'=> $request->address,
                    'max_budget' => $request->max_budget,
                    'image' => $user->image ? url('storage/' . $user->image) : null,
                    'job_title' => $user->job_title,
                    'gender' => $user->gender,
                    'aboutme' => $user->aboutme,
                ],
                'housing_preferences' => $user->housingPreferences,
                'verification' => $user->verification,
                'lifestyle_trait' => $user->lifestyleTrait,
            ]
        ], 200);
    }

    /**
     * Update user basic info
     */
    public function updateBasicInfo(UpdateBasicInfoRequest $request)
    {
        $user = Auth::user();

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            
            $imagePath = $request->file('image')->store('profiles', 'public');
            $user->image = $imagePath;
        }

        $user->update($request->only(['name', 'job_title', 'gender', 'aboutme']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ], 200);
    }

    /**
     * Update password
     */
    

    /**
     * Delete profile image
     */
    public function deleteImage()
    {
        $user = Auth::user();

        if ($user->image) {
            Storage::disk('public')->delete($user->image);
            $user->update(['image' => null]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ], 200);
    }
}