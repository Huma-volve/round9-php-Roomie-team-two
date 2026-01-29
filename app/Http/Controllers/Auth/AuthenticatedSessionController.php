<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Js;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        // Attempt login using Laravel Auth
        if (!Auth::attempt($credentials)) {
            return apiResponse(null, 'Invalid credentials.', false, 401);
        }

        $user = Auth::user();

        // Check if email verified
        if (!$user->is_verified) {
            return apiResponse(null, 'Please verify your email first.', false, 403);
        }

        // Create token for API
        $token = $user->createToken('auth_token')->plainTextToken;

        return apiResponse([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 'Login successful!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return apiResponse(null, 'No authenticated user', false, 404);
        }

        $token = $user->currentAccessToken();

        if ($token) {
            $token->delete();
            return apiResponse(true, 'Logged out successfully');
        }

        return apiResponse(
            null,
            'No active access token found',
            false,
            400
        );
    }
}
