<?php

namespace App\Events;

use App\Models\Booking;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BookingCreated implements ShouldBroadcast
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
            // أو قناة خاصة بكل أدمن
            // new PrivateChannel('admin.' . $this->booking->property->user_id),
        ];
    }

    /**
     * اسم الحدث الذي سيُبث
     */
    public function broadcastAs(): string
    {
        return 'booking.created';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->booking->id,
            'guest_name' => $this->booking->guest_name,
            'property_name' => $this->booking->property->name,
            'check_in' => $this->booking->check_in_date,
            'message' => 'حجز جديد من ' . $this->booking->guest_name,
            'created_at' => $this->booking->created_at->toDateTimeString(),
        ];
    }
}