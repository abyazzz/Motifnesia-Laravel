@extends('admin.layouts.mainLayout')

@section('title', 'Live Chat Support')

@section('content')
<div class="p-6 h-[calc(100vh-80px)]">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-3xl font-bold text-gray-800">Live Chat Support</h1>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm font-medium">
                {{ $chats->count() }} Active Chats
            </span>
        </div>
    </div>

    <!-- Chat Container -->
    <div class="grid grid-cols-3 gap-6 h-[calc(100%-120px)]">
        <!-- Left Panel: Chat List -->
        <div class="col-span-1 bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
            <div class="p-4 bg-gradient-to-r from-amber-700 to-orange-700">
                <h2 class="text-white font-bold text-lg">Customer Chats</h2>
            </div>
            
            <div class="flex-1 overflow-y-auto" id="chatListPanel">
                @if($chats->isEmpty())
                    <div class="flex flex-col items-center justify-center h-full text-gray-400 p-6">
                        <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-center">Belum ada chat</p>
                    </div>
                @else
                    @foreach($chats as $chat)
                        <a href="{{ route('admin.chat.index', ['chat_id' => $chat->id]) }}" 
                           class="block p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ $currentChat && $currentChat->id === $chat->id ? 'bg-amber-50 border-l-4 border-amber-600' : '' }}">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-amber-400 to-orange-600 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ strtoupper(substr($chat->user->name, 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h3 class="font-semibold text-gray-800 truncate">{{ $chat->user->name }}</h3>
                                        @php
                                            $unreadCount = $chat->messages()->where('sender_id', '!=', Auth::id())->where('is_read', false)->count();
                                        @endphp
                                        @if($unreadCount > 0)
                                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $unreadCount }}</span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-gray-500 truncate">{{ $chat->lastMessage ? $chat->lastMessage->message : 'No messages yet' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : '' }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Right Panel: Chat Messages -->
        <div class="col-span-2 bg-white rounded-xl shadow-lg overflow-hidden flex flex-col">
            @if($currentChat)
                <!-- Chat Header -->
                <div class="p-4 bg-gradient-to-r from-amber-700 to-orange-700 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($currentChatName, 0, 1)) }}
                        </div>
                        <div>
                            <h2 class="text-white font-bold">{{ $currentChatName }}</h2>
                            <p class="text-white/80 text-sm">{{ $currentChat->user->email ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="chatMessages" class="flex-1 overflow-y-auto p-6 bg-gray-50">
                    @if($currentChatMessages->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-gray-400">
                            <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <p>Belum ada pesan</p>
                        </div>
                    @else
                        @foreach($currentChatMessages as $message)
                            @php
                                $isAdmin = $message->sender_id === Auth::id();
                            @endphp
                            <div class="mb-4 flex {{ $isAdmin ? 'justify-end' : 'justify-start' }}" data-message-id="{{ $message->id }}">
                                <div class="max-w-[70%]">
                                    @if(!$isAdmin)
                                        <div class="flex items-start gap-2">
                                            <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center flex-shrink-0 text-white text-sm">
                                                {{ strtoupper(substr($message->sender->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <div class="bg-white rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                                                    <p class="text-gray-800">{{ $message->message }}</p>
                                                </div>
                                                <p class="text-xs text-gray-400 mt-1 ml-2">{{ $message->created_at->format('H:i') }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-end">
                                            <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                                                <p class="text-white">{{ $message->message }}</p>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-1 mr-2">{{ $message->created_at->format('H:i') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Input Area -->
                <div class="p-4 bg-white border-t border-gray-200">
                    <form id="chatForm" class="flex gap-3">
                        @csrf
                        <input type="hidden" id="chatId" value="{{ $currentChat->id }}">
                        <input 
                            type="text" 
                            id="messageInput" 
                            placeholder="Ketik balasan Anda..."
                            class="flex-1 px-4 py-3 bg-gray-100 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-500"
                            required
                        >
                        <button 
                            type="submit"
                            class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-full font-semibold hover:shadow-lg transition-all flex items-center gap-2"
                        >
                            <span>Kirim</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-24 h-24 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-lg font-medium">Pilih chat untuk memulai</p>
                </div>
            @endif
        </div>
    </div>
</div>

@if($currentChat)
<script>
let chatId = {{ $currentChat->id }};
let lastMessageId = {{ $currentChatMessages->last()->id ?? 0 }};
let pollingInterval;

// Scroll to bottom
function scrollToBottom() {
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

// Add message to chat
function addMessage(message, isAdmin) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `mb-4 flex ${isAdmin ? 'justify-end' : 'justify-start'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    if (!isAdmin) {
        messageDiv.innerHTML = `
            <div class="max-w-[70%]">
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 rounded-full bg-gray-400 flex items-center justify-center flex-shrink-0 text-white text-sm">
                        ${message.sender_name.charAt(0).toUpperCase()}
                    </div>
                    <div>
                        <div class="bg-white rounded-2xl rounded-tl-none px-4 py-2 shadow-sm">
                            <p class="text-gray-800">${message.message}</p>
                        </div>
                        <p class="text-xs text-gray-400 mt-1 ml-2">${message.created_at}</p>
                    </div>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `
            <div class="max-w-[70%]">
                <div class="flex flex-col items-end">
                    <div class="bg-gradient-to-r from-amber-600 to-orange-600 rounded-2xl rounded-tr-none px-4 py-2 shadow-sm">
                        <p class="text-white">${message.message}</p>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 mr-2">${message.created_at}</p>
                </div>
            </div>
        `;
    }
    
    chatMessages.appendChild(messageDiv);
    scrollToBottom();
    lastMessageId = message.id;
}

// Send message
document.getElementById('chatForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const messageInput = document.getElementById('messageInput');
    const message = messageInput.value.trim();
    
    if (!message) return;
    
    try {
        const response = await fetch('{{ route("admin.chat.send") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                chat_id: chatId,
                message: message
            })
        });
        
        const data = await response.json();
        
        if (data.success) {
            addMessage(data.message, true);
            messageInput.value = '';
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
});

// Poll for new messages
async function pollMessages() {
    try {
        const response = await fetch(`{{ url('/admin/live-chat') }}/${chatId}/messages?last_message_id=${lastMessageId}`);
        const data = await response.json();
        
        if (data.success && data.messages.length > 0) {
            data.messages.forEach(msg => {
                addMessage(msg, msg.is_admin);
            });
        }
    } catch (error) {
        console.error('Error polling messages:', error);
    }
}

// Start polling
scrollToBottom();
pollingInterval = setInterval(pollMessages, 3000);

// Poll chat list for updates
async function pollChatList() {
    try {
        const response = await fetch('{{ route("admin.chat.list") }}');
        const data = await response.json();
        
        if (data.success) {
            // Update unread counts in chat list
            data.chats.forEach(chat => {
                const chatElement = document.querySelector(`a[href*="chat_id=${chat.id}"]`);
                if (chatElement) {
                    const badge = chatElement.querySelector('.bg-red-500');
                    if (chat.unread_count > 0) {
                        if (!badge) {
                            // Add badge
                            const badgeHTML = `<span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">${chat.unread_count}</span>`;
                            chatElement.querySelector('.flex.items-center.justify-between').insertAdjacentHTML('beforeend', badgeHTML);
                        } else {
                            badge.textContent = chat.unread_count;
                        }
                    } else if (badge) {
                        badge.remove();
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error polling chat list:', error);
    }
}

setInterval(pollChatList, 5000);
</script>
@endif
@endsection