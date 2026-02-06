<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * القنوات التي سيتم البث عليها
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notifications'),
        ];
    }

    /**
     * اسم الحدث الذي سيُبث
     */
    public function broadcastAs(): string
    {
        return 'booking.cancelled';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'guest_name' => $this->booking->guest_name,
            'property_name' => $this->booking->property->name ?? 'غير محدد',
            'check_in' => $this->booking->check_in_date,
            'message' => 'تم إلغاء حجز ' . $this->booking->guest_name,
            'type' => 'booking_cancelled',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}