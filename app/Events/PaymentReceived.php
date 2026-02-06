<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment;
    public $booking;

    public function __construct($payment, $booking)
    {
        $this->payment = $payment;
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
        return 'payment.received';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'payment_id' => $this->payment->id ?? null,
            'booking_id' => $this->booking->id ?? null,
            'amount' => $this->payment->amount ?? 0,
            'guest_name' => $this->booking->guest_name ?? 'غير محدد',
            'property_name' => $this->booking->property->name ?? 'غير محدد',
            'message' => 'تم استلام دفعة بمبلغ ' . ($this->payment->amount ?? 0) . ' جنيه',
            'type' => 'payment_received',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}