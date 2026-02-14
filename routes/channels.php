<?php

use App\Models\Conversation;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

// القناة الافتراضية للمستخدمين
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// قناة الإشعارات الخاصة بالأدمن
Broadcast::channel('admin-notifications', function ($user) {
    return $user && $user->is_admin === true;
});

// قناة الدردشة - للـ Tenant والأدمن المسؤول عن المحادثة
Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    $conversation = Conversation::find($conversationId);
    
    // التأكد من وجود المحادثة
    if (!$conversation) {
        return false;
    }

    // السماح للـ Tenant أو الأدمن المسؤول فقط
    return $user->id === $conversation->tenant_id || 
           $user->id === $conversation->admin_id;
});