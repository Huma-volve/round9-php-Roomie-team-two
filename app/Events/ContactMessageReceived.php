<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContactMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
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
        return 'contact.received';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'email' => $this->contactMessage->email,
            'subject' => $this->contactMessage->subject,
            'message' => 'رسالة تواصل جديدة من ' . $this->contactMessage->name,
            'type' => 'contact_message',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}