<?php

namespace App\Events;

use App\Models\Conversation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ChatMessageReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $conversation;
    public $message;

    /**
     * Create a new event instance.
     *
     * @param Conversation $conversation
     * @param string|null $message - المحتوى الفعلي للرسالة
     */
    public function __construct(Conversation $conversation, $message = null)
    {
        $this->conversation = $conversation;
        $this->message = $message;
        
        Log::info('ChatMessageReceived event created', [
            'conversation_id' => $conversation->id,
            'admin_id' => $conversation->admin_id,
            'tenant_name' => $conversation->tenant->first_name . ' ' . $conversation->tenant->last_name
        ]);
    }

    /**
     * القنوات التي سيتم البث عليها
     */
    public function broadcastOn(): array
    {
        return [
            // بث للأدمن المسؤول فقط
            new PrivateChannel('admin-notifications'),
            
            // أو قناة خاصة بالأدمن المحدد (اختياري)
            // new PrivateChannel('admin.' . $this->conversation->admin_id),
        ];
    }

    /**
     * اسم الحدث الذي سيُبث
     */
    public function broadcastAs(): string
    {
        return 'chat.message.received';
    }

    /**
     * البيانات التي سيتم إرسالها
     */
    public function broadcastWith(): array
    {
        $data = [
            'id' => $this->conversation->id,
            'conversation_id' => $this->conversation->id,
            'tenant_name' => $this->conversation->tenant->first_name . ' ' . $this->conversation->tenant->last_name,
            'tenant_id' => $this->conversation->tenant_id,
            'admin_id' => $this->conversation->admin_id,
            'message_preview' => $this->message ? substr($this->message, 0, 50) : $this->conversation->last_message,
            'message' => 'رسالة جديدة من ' . $this->conversation->tenant->first_name . ' ' . $this->conversation->tenant->last_name,
            'type' => 'chat_message',
            'priority' => 'high',
            'created_at' => now()->toDateTimeString(),
        ];
        
        Log::info('Broadcasting chat message data', $data);
        
        return $data;
    }
}