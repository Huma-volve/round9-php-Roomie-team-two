<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerification;
use App\Http\Requests\VerifyCodeRequest;
use App\Http\Requests\SendPhoneVerificationRequest;
use App\Http\Requests\UploadIdDocumentRequest;
use App\Http\Requests\RejectIdRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class VerificationController extends Controller
{
    /**
     * Send email verification code
     */
    public function sendEmailVerification()
    {
        $user = Auth::user();
        
        // Generate 6-digit code
        $code = rand(100000, 999999);
        
        // Store code in cache for 10 minutes
        Cache::put("email_verification_{$user->id}", $code, now()->addMinutes(10));
        
        // Send email (you need to create the email template)
        // Mail::to($user->email)->send(new VerificationCodeEmail($code));
        
        // For testing, return the code (remove in production)
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your email',
            'code' => $code // Remove this in production
        ], 200);
    }

    /**
     * Verify email with code
     */
    public function verifyEmail(VerifyCodeRequest $request)
    {
        $user = Auth::user();
        $storedCode = Cache::get("email_verification_{$user->id}");

        if (!$storedCode) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code expired. Please request a new one.'
            ], 400);
        }

        if ($storedCode != $request->code) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code'
            ], 400);
        }

        // Mark email as verified
        $user->update(['email_verified_at' => now()]);
        
        $verification = UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            ['email_verified' => true]
        );

        // Clear the code from cache
        Cache::forget("email_verification_{$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully',
            'data' => $verification
        ], 200);
    }

    /**
     * Send phone verification code
     */
    public function sendPhoneVerification(SendPhoneVerificationRequest $request)
    {
        $user = Auth::user();
        
        // Generate 6-digit code
        $code = rand(100000, 999999);
        
        // Store phone and code in cache for 10 minutes
        Cache::put("phone_verification_{$user->id}", [
            'phone' => $request->phone,
            'code' => $code
        ], now()->addMinutes(10));
        
        // Send SMS (integrate with SMS service like Twilio, Nexmo, etc.)
        // SMS::send($request->phone, "Your verification code is: {$code}");
        
        // For testing, return the code (remove in production)
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your phone',
            'code' => $code // Remove this in production
        ], 200);
    }

    /**
     * Verify phone with code
     */
    public function verifyPhone(VerifyCodeRequest $request)
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

        // Mark phone as verified
        $verification = UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            ['phone_verified' => true]
        );

        // Clear the code from cache
        Cache::forget("phone_verification_{$user->id}");

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully',
            'data' => $verification
        ], 200);
    }

    /**
     * Upload ID document for verification
     */
    public function uploadIdDocument(UploadIdDocumentRequest $request)
    {
        $user = Auth::user();

        // Store document
        $path = $request->file('id_document')->store('id_documents', 'public');

        // Store verification request (admin will review)
        $verification = UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'id_document_path' => $path,
                'id_type' => $request->id_type,
                'id_verified' => false // Admin needs to approve
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'ID document uploaded successfully. It will be reviewed by admin.',
            'data' => [
                'document_path' => url('storage/' . $path),
                'id_type' => $request->id_type,
                'status' => 'pending_review'
            ]
        ], 200);
    }

    /**
     * Get verification status
     */
    public function getStatus()
    {
        $user = Auth::user();
        $verification = UserVerification::firstOrCreate(
            ['user_id' => $user->id],
            [
                'email_verified' => false,
                'phone_verified' => false,
                'id_verified' => false,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => [
                'email_verified' => $verification->email_verified,
                'phone_verified' => $verification->phone_verified,
                'id_verified' => $verification->id_verified,
                'id_status' => $verification->id_verified ? 'verified' : 
                              ($verification->id_document_path ? 'pending_review' : 'not_uploaded')
            ]
        ], 200);
    }

    /**
     * Admin: Approve ID verification
     */
    public function approveId($userId)
    {
        // Add admin middleware check
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $verification = UserVerification::where('user_id', $userId)->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'User verification not found'
            ], 404);
        }

        $verification->update(['id_verified' => true]);

        return response()->json([
            'success' => true,
            'message' => 'ID verification approved successfully'
        ], 200);
    }

    /**
     * Admin: Reject ID verification
     */
    public function rejectId(RejectIdRequest $request, $userId)
    {
        // Add admin middleware check
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $verification = UserVerification::where('user_id', $userId)->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'User verification not found'
            ], 404);
        }

        $verification->update([
            'id_verified' => false,
            'rejection_reason' => $request->reason
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ID verification rejected'
        ], 200);
    }
}