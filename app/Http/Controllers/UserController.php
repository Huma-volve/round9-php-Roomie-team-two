<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function destroy(Request $request): JsonResponse
    {
        $user = $request->user();

        $user->tokens()->delete();

        if (method_exists($user, 'otps')) $user->otps()->delete();
        if (method_exists($user, 'bookings')) $user->bookings()->delete();
        if (method_exists($user, 'reviews')) $user->reviews()->delete();

        $user->delete();

        return apiResponse(
            null,
            'User account and all related data deleted successfully',
            true,
            200
        );
    }
}
