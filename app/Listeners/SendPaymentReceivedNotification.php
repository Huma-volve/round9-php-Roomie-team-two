<?php

namespace App\Listeners;

use App\Events\PaymentReceived;
use App\Models\AdminNotification;
use App\Models\User;

class SendPaymentReceivedNotification
{
    /**
     * Handle the event.
     */
    public function handle(PaymentReceived $event): void
    {
        $payment = $event->payment;
        $booking = $event->booking;
        $admins = User::where('is_admin', true)->get();
        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'payment_received',
                'title' => 'دفعة جديدة',
                'message' => "تم استلام دفعة بقيمة {$payment->amount} للحجز #{$booking->id}",
                'notifiable_type' => get_class($booking),
                'notifiable_id' => $booking->id,
                'priority' => 'medium',
                // 'action_url' => route('admin.bookings.show', $booking->id),
                'data' => [
                    'payment_id' => $payment->id,
                    'booking_id' => $booking->id,
                    'amount' => $payment->amount,
                ],
            ]);
        }
    }
}