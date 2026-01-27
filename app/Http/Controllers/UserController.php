<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(Request $request)
    {
        $user = $request->user();

        $user->tokens()->delete();

        if (method_exists($user, 'otps')) $user->otps()->delete();
        if (method_exists($user, 'bookings')) $user->bookings()->delete();
        if (method_exists($user, 'reviews')) $user->reviews()->delete();

        $user->delete();

        return response()->json([
            'message' => 'User account and all related data deleted successfully'
        ]);
    }
}
