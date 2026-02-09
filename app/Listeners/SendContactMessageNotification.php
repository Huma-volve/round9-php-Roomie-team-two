<?php

namespace App\Listeners;

use App\Events\ContactMessageReceived;
use App\Models\AdminNotification;
use App\Models\User;

class SendContactMessageNotification
{
    /**
     * Handle the event.
     */
    public function handle(ContactMessageReceived $event): void
    {
        $contactMessage = $event->contactMessage;
        $admins = User::where('is_admin', true)->get();

        foreach ($admins as $admin) {
            $notification = AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'new_contact_message',
                'title' => 'رسالة تواصل جديدة',
                'message' => "رسالة جديدة من {$contactMessage->name}",
                'notifiable_type' => get_class($contactMessage),
                'notifiable_id' => $contactMessage->id,
                'priority' => 'medium',
                'data' => json_encode([
                    'contact_message_id' => $contactMessage->id,
                    'sender_name' => $contactMessage->name,
                    'sender_email' => $contactMessage->email,
                ]),
            ]);

            // ✅ Log بعد إنشاء الإشعار
            \Log::info('Contact notification created', [
                'notification_id' => $notification->id,
                'admin_id' => $admin->id,
                'message' => $notification->message
            ]);
        }
        
        // ✅ الـ Event نفسه هيبث لأنه implements ShouldBroadcast
        \Log::info('ContactMessageReceived event will be broadcast', [
            'contact_id' => $contactMessage->id,
            'contact_name' => $contactMessage->name
        ]);
    }
}