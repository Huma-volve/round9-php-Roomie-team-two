<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;




Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);

    return $user->id === $conversation->tenant_id || $user->id === $conversation->admin_id;
});