<?php

namespace App\Listeners;

use App\Events\BookingCancelled;
use App\Models\AdminNotification;
use App\Models\User;

class SendBookingCancelledNotification
{
    /**
     * Handle the event.
     */
    public function handle(BookingCancelled $event): void
    {
        $booking = $event->booking;
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'booking_cancellation',
                'title' => 'إلغاء حجز',
                'message' => "تم إلغاء الحجز رقم #{$booking->id}",
                'notifiable_type' => get_class($booking),
                'notifiable_id' => $booking->id,
                'priority' => 'medium',
                'action_url' => route('admin.bookings.show', $booking->id),
                'data' => [
                    'booking_id' => $booking->id,
                    'user_name' => $booking->user->first_name . ' ' . $booking->user->last_name,
                    'cancelled_at' => now(),
                ],
            ]);
        }
    }
}