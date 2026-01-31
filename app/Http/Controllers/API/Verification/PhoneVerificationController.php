<?php

namespace App\Http\Controllers\Api\Verification;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use App\Http\Requests\SendPhoneVerificationRequest;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PhoneVerificationController extends Controller
{
    /**
     * Send phone verification code
     */
    public function send(SendPhoneVerificationRequest $request)
    {
        $user = Auth::user();
        
        // Check if phone already verified
        $verification = UserVerification::where('user_id', $user->id)->first();
        
        if ($verification && $verification->phone_verified) {
            return response()->json([
                'success' => false,
                'message' => 'Phone is already verified'
            ], 400);
        }
        
        // Generate 6-digit code
        $code = rand(100000, 999999);
        
        // Store phone and code in cache for 10 minutes
        Cache::put("phone_verification_{$user->id}", [
            'phone' => $request->phone,
            'code' => $code
        ], now()->addMinutes(10));
        
        // Send SMS (integrate with Twilio, Nexmo, etc.)
        // SMS::send($request->phone, "Your verification code is: {$code}");
        
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your phone',
            'code' => $code // Remove in production
        ], 200);
    }

    /**
     * Verify phone with code
     */
    public function verify(VerifyCodeRequest $request)
    {
        $user = Auth::user();
        $storedData = Cache::get("phone_verification_{$user->id}");

        if (!$storedData) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired. Please request a new one.'
            ], 400);
        }

        if ($storedData['code'] != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code'
            ], 400);
        }

        // Mark phone as verified and save phone number
        $verification = UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone_verified' => true,
                'phone_number' => $storedData['phone']
            ]
        );

        // Clear the code from cache
        Cache::forget("phone_verification_{$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully',
            'data' => [
                'phone_verified' => $verification->phone_verified,
                'phone_number' => $verification->phone_number
            ]
        ], 200);
    }

    /**
     * Get phone verification status
     */
    public function status()
    {
        $user = Auth::user();
        $verification = UserVerification::where('user_id', $user->id)->first();

        return response()->json([
            'success' => true,
            'data' => [
                'phone_verified' => $verification ? $verification->phone_verified : false,
                'phone_number' => $verification ? $verification->phone_number : null
            ]
        ], 200);
    }
}