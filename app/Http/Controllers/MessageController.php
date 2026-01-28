<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\MessageSent;
use App\Models\Conversation;
use Illuminate\Http\Request;
use App\Http\Resources\MessageStoreResource;
use App\Http\Resources\SearchMessageResource;

class MessageController extends Controller
{
    public function store(Request $request, $id)
    {
        $conversation = Conversation::findOrFail($id);
        $request->validate([
            'message_body' => 'required|string',
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message_body' => $request->message_body,
        ]);


        $conversation->update([
            'last_message' => $request->message_body,
            'last_message_at' => now(),
        ]);
        
        broadcast(new MessageSent($message))->toOthers();
        return apiResponse(MessageStoreResource::make($message), 'Message sent successfully', true, 200);

    }

    public function search(Request $request )
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

            return apiResponse(SearchMessageResource::collection($results), 'Search results', true, 200);


    }
}
