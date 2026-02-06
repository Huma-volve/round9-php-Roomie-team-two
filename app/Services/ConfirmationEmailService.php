<?php

namespace App\Services;

use App\Mail\BookingConfirmationMail;
use App\Models\Payment;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConfirmationEmailService
{
    /**
     * Sends a booking confirmation email to the user associated with the given payment.
     *
     * @param Payment $payment
     * @throws Exception
     */
    public static function send(Payment $payment)
    {
        try {
            $booking = $payment->booking;
            $user = $booking->user;

            Mail::to($user->email)
                ->send(new BookingConfirmationMail($payment));

            Log::info("Booking Confirmation Email sent for reservation: {$payment->reservation_code}");
        } catch (Exception $e) {
            Log::error("Failed to send confirmation email: {$e->getMessage()}");
            throw new Exception("Failed to send confirmation email");
        }
    }
}
