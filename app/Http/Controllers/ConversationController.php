<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\MessageResource;
use App\Http\Resources\ConversationResource;

class ConversationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $conversations = Conversation::query()
            ->when($user->is_admin, function ($query) use ($user) {
                return $query->where('admin_id', $user->id);
            }, function ($query) use ($user) {
                return $query->where('tenant_id', $user->id);
            })
            ->with(['admin', 'tenant'])
            ->orderBy('last_message_at', 'desc') 
            ->get();


            return apiResponse(ConversationResource::collection($conversations), 'Conversations', true, 200);
    }

    public function startConversation($adminId)
    {
        $admin = User::findOrFail($adminId);
        $tenant = Auth::user();

        $conversation = Conversation::create([
            'tenant_id' => $tenant->id,
            'admin_id' => $admin->id,
        ]);

        return apiResponse(ConversationResource::make($conversation), 'Conversation started successfully', true, 200);
    }

    public function show($id)
    {
        $conversation = Conversation::findOrFail($id);

        if (auth()->id() !== $conversation->tenant_id && auth()->id() !== $conversation->admin_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        $messages = $conversation->messages()
            ->with('sender')
            ->oldest()
            ->paginate(20);

        if ($userId = auth()->id()) {
            $conversation->messages()
                ->where('sender_id', '!=', $userId)
                ->update(['is_read' => true]);
        }

        return apiResponse([
            'conversation' => ConversationResource::make($conversation),
            'messages' => MessageResource::collection($messages),
        ], 'Conversation', true, 200);

    }
}
