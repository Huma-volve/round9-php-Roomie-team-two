<?php

namespace App\Listeners;

use App\Events\BookingCreated;
use App\Models\AdminNotification;
use App\Models\User;

class SendNewBookingNotification
{
    /**
     * Handle the event.
     */
    public function handle(BookingCreated $event): void
    {
        $booking = $event->booking;
       $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'new_booking',
                'title' => 'حجز جديد',
                'message' => "حجز جديد رقم #{$booking->id} من {$booking->user->first_name} {$booking->user->last_name}",
                'notifiable_type' => get_class($booking),
                'notifiable_id' => $booking->id,
                'priority' => 'high',
                // 'action_url' => route('admin.bookings.show', $booking->id),
                'data' => [
                    'booking_id' => $booking->id,
                    'user_name' => $booking->user->first_name . ' ' . $booking->user->last_name,
                    'check_in' => $booking->check_in_date,
                    'check_out' => $booking->check_out_date,
                    'total_price' => $booking->total_price,
                ],
            ]);
        }
    }
}