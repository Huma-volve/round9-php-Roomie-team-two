<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageReceived;
use App\Events\MessageSent;
use App\Http\Resources\MessageStoreResource;
use App\Http\Resources\SearchMessageResource;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * إرسال رسالة جديدة
     */
    public function store(Request $request, $id)
    {
        $conversation = Conversation::find($id);

        if (!$conversation) {
            return apiResponse([], 'Conversation not found', false, 404);
        }

        $request->validate([
            'message_body' => 'required|string|max:1000',
        ]);

        // إنشاء الرسالة
        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message_body' => $request->message_body,
        ]);

        if (!$message) {
            return apiResponse([], 'Message not sent', false, 400);
        }

        $conversation->update([
            'last_message' => $request->message_body,
            'last_message_at' => now(),
        ]);

        // Broadcast للـ Real-time Chat
        broadcast(new MessageSent($message))->toOthers();

        // 🔥 إطلاق Event لإشعار الأدمن (فقط لو المرسل هو Tenant)
        if (auth()->id() === $conversation->tenant_id) {
            event(new ChatMessageReceived($conversation));
        }

        return apiResponse(
            MessageStoreResource::make($message), 
            'Message sent successfully', 
            true, 
            200
        );
        broadcast(new MessageSent($message))->toOthers();
        return apiResponse(MessageStoreResource::make($message), 'Message sent successfully', true, 200);
    }

    /**
     * البحث في الرسائل
     */
    public function search(Request $request)
    {
        $search = $request->query('q');
        $user = auth()->user();

        if (!$search) {
            return apiResponse([], 'Please provide a search query.', false, 400);
        }

        $results = Message::where('message_body', 'LIKE', "%{$search}%")
            ->whereHas('conversation', function ($query) use ($user) {
                $query->where('tenant_id', $user->id)
                    ->orWhere('admin_id', $user->id);
            })
            ->with(['conversation.tenant', 'conversation.admin']) 
            ->latest()
            ->get();

        return apiResponse(
            SearchMessageResource::collection($results), 
            'Search results', 
            true, 
            200
        );
    }
}