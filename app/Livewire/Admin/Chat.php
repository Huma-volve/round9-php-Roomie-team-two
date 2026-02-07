<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $search = '';
    public $filter = 'all'; // all | unread | read
    public $selectedConversationId = null;
    public $message = '';

    /* =====================
        Select Conversation
    ======================*/
    public function selectConversation($id)
    {
        $this->selectedConversationId = $id;

        Message::where('conversation_id', $id)
            ->where('sender_id', '!=', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /* =====================
        Send Message
    ======================*/
    public function sendMessage()
    {
        if (!$this->message || !$this->selectedConversationId) return;

        Message::create([
            'conversation_id' => $this->selectedConversationId,
            'sender_id' => Auth::id(),
            'message_body' => $this->message,
            'is_read' => false,
        ]);

        Conversation::where('id', $this->selectedConversationId)->update([
            'last_message' => $this->message,
            'last_message_at' => now(),
        ]);

        $this->message = '';
    }

    /* =====================
        Conversations
    ======================*/
    public function getConversationsProperty()
    {
        return Conversation::query()
            ->where('admin_id', 1)

            ->when($this->search, function ($q) {
                $q->where(function ($qq) {
                    // 🔍 Search by user name
                    $qq->whereHas('tenant', function ($u) {
                        $u->where('name', 'like', '%' . $this->search . '%');
                    })

                        // 🔍 OR search by message content
                        ->orWhereHas('messages', function ($m) {
                            $m->where('message_body', 'like', '%' . $this->search . '%');
                        })

                        // 🔍 OR last message
                        ->orWhere('last_message', 'like', '%' . $this->search . '%');
                });
            })

            ->when($this->filter === 'unread', function ($q) {
                $q->whereHas(
                    'messages',
                    fn($m) =>
                    $m->where('is_read', false)
                );
            })

            ->when($this->filter === 'read', function ($q) {
                $q->whereDoesntHave(
                    'messages',
                    fn($m) =>
                    $m->where('is_read', false)
                );
            })

            ->with('tenant')
            ->orderByDesc('last_message_at')
            ->get();
    }

    /* =====================
        Messages
    ======================*/
    public function getMessagesProperty()
    {
        if (!$this->selectedConversationId) return collect();

        return Message::where('conversation_id', $this->selectedConversationId)
            ->orderBy('created_at')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.chat');
    }
}
