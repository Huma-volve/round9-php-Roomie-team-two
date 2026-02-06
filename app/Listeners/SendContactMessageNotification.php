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
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            AdminNotification::create([
                'admin_id' => $admin->id,
                'type' => 'new_contact_message',
                'title' => 'رسالة تواصل جديدة',
                'message' => "رسالة جديدة من {$contactMessage->name}",
                'notifiable_type' => get_class($contactMessage),
                'notifiable_id' => $contactMessage->id,
                'priority' => 'medium',
                'action_url' => route('admin.contact-messages.show', $contactMessage->id),
                'data' => [
                    'contact_message_id' => $contactMessage->id,
                    'sender_name' => $contactMessage->name,
                    'sender_email' => $contactMessage->email,
                ],
            ]);
        }
    }
}