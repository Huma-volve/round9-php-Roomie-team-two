<?php

namespace App\Listeners;

use App\Events\ChatMessageReceived;
use App\Models\AdminNotification;

class SendChatMessageNotification
{
    /**
     * Handle the event.
     */
    public function handle(ChatMessageReceived $event): void
    {
        $conversation = $event->conversation;

        // إرسال الإشعار للأدمن المسؤول عن المحادثة فقط
        AdminNotification::create([
            'admin_id' => $conversation->admin_id,
            'type' => 'new_chat_message',
            'title' => 'رسالة جديدة',
            'message' => "رسالة جديدة من {$conversation->tenant->first_name} {$conversation->tenant->last_name}",
            'notifiable_type' => get_class($conversation),
            'notifiable_id' => $conversation->id,
            'priority' => 'high',
            'action_url' => route('admin.chats.show', $conversation->id),
            'data' => [
                'conversation_id' => $conversation->id,
                'tenant_name' => $conversation->tenant->first_name . ' ' . $conversation->tenant->last_name,
                'last_message' => $conversation->last_message,
            ],
        ]);
    }
}