<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;

class OtpService
{
    public static function send(User $user, string $type): void
    {
        Otp::where('user_id', $user->id)
            ->where('type', $type)
            ->delete();

        $otp_code = random_int(100000, 999999);

        Otp::create([
            'user_id' => $user->id,
            'otp_code' => $otp_code,
            'type' => $type,
            'expires_at' => now()->addMinutes(5),
        ]);

        Mail::to($user->email)->send(new OtpMail($otp_code));
    }
}
