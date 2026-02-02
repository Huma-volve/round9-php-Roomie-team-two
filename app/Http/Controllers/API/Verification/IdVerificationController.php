<?php

namespace App\Http\Controllers\Api\Verification;

use App\Http\Controllers\Controller;
use App\Models\UserVerification;
use App\Http\Requests\UploadIdDocumentRequest;
use App\Http\Requests\RejectIdRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class IdVerificationController extends Controller
{
    /**
     * Upload ID document for verification
     */
    public function upload(UploadIdDocumentRequest $request)
    {
        $user = Auth::user();

        // Check if ID already verified
        $verification = UserVerification::where('user_id', $user->id)->first();
        
        if ($verification && $verification->id_verified) {
            return response()->json([
                'success' => false,
                'message' => 'ID is already verified'
            ], 400);
        }

        // Delete old document if exists
        if ($verification && $verification->id_document_path) {
            Storage::disk('public')->delete($verification->id_document_path);
        }

        // Store new document
        $path = $request->file('id_document')->store('id_documents', 'public');

        // Store verification request (admin will review)
        $verification = UserVerification::updateOrCreate(
            ['user_id' => $user->id],
            [
                'id_document_path' => $path,
                'id_type' => $request->id_type,
                'id_verified' => false,
                'rejection_reason' => null // Clear previous rejection
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
     * Get ID verification status
     */
    public function status()
    {
        $user = Auth::user();
        $verification = UserVerification::where('user_id', $user->id)->first();

        $status = 'not_uploaded';
        if ($verification && $verification->id_verified) {
            $status = 'verified';
        } elseif ($verification && $verification->id_document_path) {
            $status = 'pending_review';
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id_verified' => $verification ? $verification->id_verified : false,
                'id_type' => $verification ? $verification->id_type : null,
                'document_path' => $verification && $verification->id_document_path 
                    ? url('storage/' . $verification->id_document_path) 
                    : null,
                'status' => $status,
                'rejection_reason' => $verification ? $verification->rejection_reason : null
            ]
        ], 200);
    }

    /**
     * Admin: Approve ID verification
     */
    public function approve($userId)
    {
        // Check admin permission
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        $verification = UserVerification::where('user_id', $userId)->first();

        if (!$verification) {
            return response()->json([
                'success' => false,
                'message' => 'User verification not found'
            ], 404);
        }

        if (!$verification->id_document_path) {
            return response()->json([
                'success' => false,
                'message' => 'No ID document uploaded'
            ], 400);
        }

        $verification->update([
            'id_verified' => true,
            'rejection_reason' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'ID verification approved successfully',
            'data' => [
                'user_id' => $userId,
                'id_verified' => true
            ]
        ], 200);
    }

    /**
     * Admin: Reject ID verification
     */
    public function reject(RejectIdRequest $request, $userId)
    {
        // Check admin permission
        if (!Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
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
            'message' => 'ID verification rejected',
            'data' => [
                'user_id' => $userId,
                'id_verified' => false,
                'rejection_reason' => $request->reason
            ]
        ], 200);
    }
}