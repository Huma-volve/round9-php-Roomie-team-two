<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ✅ غيّر
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class BookingCancelled implements ShouldBroadcastNow // ✅ غيّر
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
        
        // ✅ إضافة Log
        Log::info('BookingCancelled event created', [
            'booking_id' => $booking->id,
            'guest_name' => $booking->guest_name
        ]);
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'booking.cancelled';
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->booking->id,
            'guest_name' => $this->booking->guest_name,
            'property_name' => $this->booking->property->name ?? 'غير محدد',
            'check_in' => $this->booking->check_in_date,
            'message' => 'تم إلغاء حجز ' . $this->booking->guest_name,
            'type' => 'booking_cancelled',
            'created_at' => now()->toDateTimeString(),
        ];
        
        // ✅ إضافة Log
        Log::info('Broadcasting booking cancellation data', $data);
        
        return $data;
    }
}