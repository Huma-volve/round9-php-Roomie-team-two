<?php

namespace App\Http\Controllers\Api\Verification;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use App\Http\Requests\VerifyCodeRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    /**
     * Send email verification code
     */
    public function send()
    {
        $user = Auth::user();
        
        Log::info('Email verification send attempt', [
            'user_id' => $user->id,
            'email' => $user->email,
            'timestamp' => now()
        ]);
        
        // Check if email already verified من جدول users
        if ($user->is_verified == 1)  {
            Log::warning('Email verification send failed - already verified', [
                'user_id' => $user->id,
                'email' => $user->email,
                'is_verified' => $user->is_verified,
                'verified_at' => $user->email_verified_at
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Email is already verified'
            ], 400);
        }
        
        // Generate 6-digit code
        $code = rand(100000, 999999);
        
        Log::info('Email verification code generated', [
            'user_id' => $user->id,
            'email' => $user->email,
            'code' => $code, // ⚠️ Remove in production for security
            'expires_at' => now()->addMinutes(10)
        ]);
        
        // Store code in cache for 10 minutes
        Cache::put("email_verification_{$user->id}", $code, now()->addMinutes(10));
        
        Log::info('Email verification code stored in cache', [
            'user_id' => $user->id,
            'cache_key' => "email_verification_{$user->id}",
            'ttl' => '10 minutes'
        ]);
        
        // Send email
        try {
            // Mail::to($user->email)->send(new VerificationCodeEmail($code));
            
            Log::info('Email verification code sent successfully', [
                'user_id' => $user->id,
                'email' => $user->email,
                'method' => 'email'
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send email verification code', [
                'user_id' => $user->id,
                'email' => $user->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email',
            'code' => $code // Remove in production
        ], 200);
    }

    /**
     * Verify email with code
     */
    public function verify(VerifyCodeRequest $request)
{
    $user = Auth::user();
    
    Log::info('Email verification attempt', [
        'user_id' => $user->id,
        'email' => $user->email,
        'code_submitted' => $request->code,
        'timestamp' => now()
    ]);
    
    $storedCode = Cache::get("email_verification_{$user->id}");

    if (!$storedCode) {
        Log::warning('Email verification failed - code expired', [
            'user_id' => $user->id,
            'email' => $user->email,
            'cache_key' => "email_verification_{$user->id}"
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Verification code expired. Please request a new one.'
        ], 400);
    }

    if ($storedCode != $request->code) {
        Log::warning('Email verification failed - invalid code', [
            'user_id' => $user->id,
            'email' => $user->email,
            'submitted_code' => $request->code,
            'stored_code' => $storedCode // ⚠️ Remove in production
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid verification code'
        ], 400);
    }

    Log::info('Email verification code matched', [
        'user_id' => $user->id,
        'email' => $user->email
    ]);

    // Mark email as verified في جدول users
    $user->update([
        'email_verified_at' => now(),
        'is_verified' => 1
    ]);
    
    // ⭐ Refresh the model to get updated values from database
    $user->refresh();
    
    Log::info('User verification updated', [
        'user_id' => $user->id,
        'email' => $user->email,
        'is_verified' => $user->is_verified,
        'verified_at' => $user->email_verified_at
    ]);
    
    // تحديث جدول user_verifications (اختياري)
    $verification = UserVerification::updateOrCreate(
        ['user_id' => $user->id],
        ['email_verified' => true]
    );

    Log::info('UserVerification record updated', [
        'user_id' => $user->id,
        'verification_id' => $verification->id,
        'email_verified' => $verification->email_verified
    ]);

    // Clear the code from cache
    Cache::forget("email_verification_{$user->id}");
    
    Log::info('Email verification cache cleared', [
        'user_id' => $user->id,
        'cache_key' => "email_verification_{$user->id}"
    ]);

    Log::info('Email verification completed successfully', [
        'user_id' => $user->id,
        'email' => $user->email,
        'is_verified' => $user->is_verified,
        'verified_at' => $user->email_verified_at
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Email verified successfully',
        'data' => [
            'email_verified' => true,
            'is_verified' => $user->is_verified,
            'verified_at' => $user->email_verified_at,
            'user_verification' => $user,
        ]
    ], 200);
}

    /**
     * Get email verification status
     */
    public function status()
    {
        $user = Auth::user();
        
        Log::info('Email verification status check', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);
        
        // احصل على الحالة من جدول users مباشرة
        $isVerified = $user->is_verified == 1 ;
        
        $status = [
            'email_verified' => $isVerified,
            'is_verified' => $user->is_verified,
            'verified_at' => $user->email_verified_at,
            'user_verification' => $user,
        ];

        Log::info('Email verification status retrieved', [
            'user_id' => $user->id,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'data' => $status
        ], 200);
    }
}