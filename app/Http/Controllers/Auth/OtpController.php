<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class OtpController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        $otp = Otp::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            return response()->json(['message' => 'Invalid or expired OTP'], 422);
        }

        $user->is_verified = true;
        $user->save();

        $otp->delete();

        return response()->json(['message' => 'Email verified successfully']);
    }



    public function verifyResetOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required',
        ]);

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

        // OTP صحيح → احذف الـ OTP
        $otp->delete();

        // إنشاء Token لإعادة تعيين كلمة المرور
        $token = \Illuminate\Support\Facades\Password::createToken($user);

        // السماح للمستخدم بتغيير الباسورد
        return response()->json([
            'next_step' => 'reset_password',
            'message' => 'OTP verified! You can now reset your password.',
            'email' => $user->email,
            'token' => $token, // Return token for reset
        ]);
    }

    public function resendOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'type' => 'required|string', // 'register' أو 'reset_password'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        // احذف أي OTP قديمة لنفس النوع
        Otp::where('user_id', $user->id)
            ->where('type', $request->type)
            ->delete();

        // Generate OTP جديد
        $otp_code = rand(100000, 999999);

        $otp = Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otp_code,
            'type' => $request->type,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send OTP email
        Mail::to($user->email)->send(new OtpMail($otp_code));

        return response()->json([
            'message' => 'OTP resent successfully.',
            'email' => $user->email,
            'next_step' => $request->type === 'register' ? 'verify_otp' : 'verify_otp_reset',
        ]);
    }
}
