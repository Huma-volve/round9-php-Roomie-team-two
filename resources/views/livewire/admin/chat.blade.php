<div class="max-w-7xl mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-red-500 mb-6">My Messages</h1>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">

        <!-- Conversations -->
        <div class="md:col-span-1 bg-black/40 border border-red-700/40 rounded-xl p-4 h-[75vh] overflow-y-auto">

            <h3 class="text-xl font-semibold text-red-400 mb-4">Chats</h3>

            <!-- Search -->
            <input type="text" wire:model.live="search" placeholder="Search chats..."
                class="w-full mb-3 p-2 bg-black/50 border border-red-700/40 rounded-lg">

            <!-- Filters -->
            <div class="flex gap-2 mb-4">
                @foreach(['all','unread','read'] as $f)
                <button wire:click="$set('filter','{{ $f }}')" class="px-3 py-1 rounded
                        {{ $filter === $f ? 'bg-red-600' : 'bg-black/50' }}">
                    {{ ucfirst($f) }}
                </button>
                @endforeach
            </div>

            <!-- List -->
            <div class="space-y-3">
                @forelse($this->conversations as $conversation)
                @php $tenant = $conversation->tenant; @endphp

                <button wire:click="selectConversation({{ $conversation->id }})" class="w-full p-3 rounded-lg bg-black/50 hover:bg-black/70 border border-red-700/40 text-left
                        {{ $selectedConversationId === $conversation->id ? 'bg-red-700/20 border-red-500' : '' }}">

                    <div class="flex items-center gap-3">
                        <img src="{{ $tenant->Image
                                ? asset('storage/users/'.$tenant->Image)
                                : asset('user-assets/assets/images/user.jpg') }}" class="w-10 h-10 rounded-full">

                        <div class="flex-1">
                            <p class="font-semibold">{{ $tenant->name }}</p>
                            <p class="text-gray-400 text-sm truncate">
                                {{ $conversation->last_message }}
                            </p>
                        </div>

                        @if($conversation->unreadMessages()->count())
                        <span class="bg-red-600 text-xs px-2 py-1 rounded-full">
                            {{ $conversation->unreadMessages()->count() }}
                        </span>
                        @endif
                    </div>
                </button>
                @empty
                <p class="text-gray-400">No chats</p>
                @endforelse
            </div>
        </div>

        <!-- Chat Box -->
        <div class="md:col-span-3 bg-black/40 border border-red-700/40 rounded-xl h-[75vh] flex flex-col">

            <!-- Header -->
            @php
            $selectedConversation = $this->conversations
            ->firstWhere('id', $selectedConversationId);
            @endphp

            @if($selectedConversation)
            <div class="p-4 border-b border-red-700/40 flex items-center gap-3">
                <img src="{{ $selectedConversation->tenant->Image
                        ? asset('storage/users/'.$selectedConversation->tenant->Image)
                        : asset('user-assets/assets/images/user.jpg') }}" class="w-12 h-12 rounded-full">

                <h3 class="text-xl font-semibold">
                    {{ $selectedConversation->tenant->name }}
                </h3>
            </div>
            @endif

            <!-- Messages -->
            <div class="flex-1 p-4 overflow-y-auto space-y-4">
                @if(!$selectedConversationId)
                <p class="text-center text-gray-400 mt-10">
                    Select a conversation
                </p>
                @else
                @foreach($this->messages as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="{{ $msg->sender_id === auth()->id() ? 'bg-red-600' : 'bg-gray-800' }}
                                p-3 rounded-xl max-w-xs">
                        {{ $msg->message_body }}
                    </div>
                </div>
                @endforeach
                @endif
            </div>

            <!-- Input -->
            @if($selectedConversationId)
            <div class="p-4 border-t border-red-700/40">
                <div class="flex gap-3">
                    <input type="text" wire:model.defer="message" placeholder="Type a message..."
                        class="flex-1 p-3 bg-black/50 border border-red-700/40 rounded-lg">

                    <button wire:click="sendMessage" class="bg-red-600 hover:bg-red-700 px-5 rounded-lg font-semibold">
                        Send
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>