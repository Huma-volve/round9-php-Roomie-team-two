<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Requests\Auth\VerifyResetOtpRequest;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtpController extends Controller
{
    public function verify(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->is_verified) {
            return response()->json([
                'message' => 'Email already verified.'
            ], 409);
        }

        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->where('type', 'register')
            ->where('expires_at', '>', now())
            ->first();


        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 422);
        }

        $user->is_verified = true;
        $user->save();
        $token = $user->createToken('auth_token')->plainTextToken;
        $otp->delete();
        return apiResponse([
            'data' => $token,
            'message' => 'Email verified successfully'
        ], 200);
    }



    public function verifyResetOtp(VerifyResetOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->where('type', 'reset_password')
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json([
                'next_step' => 'verify_otp_reset',
                'message' => 'Invalid or expired OTP.'
            ], 422);
        }

        $otp->delete();

        $token = \Illuminate\Support\Facades\Password::createToken($user);

        return response()->json([
            'next_step' => 'reset_password',
            'message' => 'OTP verified! You can now reset your password.',
            'email' => $user->email,
            'token' => $token,
        ]);
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        OtpService::send($user, $request->type);

        return response()->json([
            'message' => 'OTP resent successfully.',
            'email' => $user->email,
            'next_step' => $request->type === 'register'
                ? 'verify_otp'
                : 'verify_otp_reset',
        ]);
    }
}
