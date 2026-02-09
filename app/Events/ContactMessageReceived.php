<?php

namespace App\Events;

use App\Models\ContactMessage;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // ✅ غيّر هنا
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ContactMessageReceived implements ShouldBroadcastNow // ✅ غيّر هنا
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $contactMessage;

    public function __construct(ContactMessage $contactMessage)
    {
        $this->contactMessage = $contactMessage;
        
        Log::info('ContactMessageReceived event created', [
            'contact_id' => $contactMessage->id,
            'contact_name' => $contactMessage->name
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
        return 'contact.received';
    }

    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->contactMessage->id,
            'name' => $this->contactMessage->name,
            'email' => $this->contactMessage->email,
            'subject' => $this->contactMessage->subject,
            'message' => 'رسالة تواصل جديدة من ' . $this->contactMessage->name,
            'type' => 'contact_message',
            'created_at' => now()->toDateTimeString(),
        ];
        
        Log::info('Broadcasting contact message data', $data);
        
        return $data;
    }
}