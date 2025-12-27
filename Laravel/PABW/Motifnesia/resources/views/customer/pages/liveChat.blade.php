@extends('customer.layouts.mainLayout')

@section('container')
<div class="max-w-4xl mx-auto pt-24 pb-12 px-4">
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col" style="height: calc(100vh - 180px);">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-amber-700 to-orange-700 px-6 py-4 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg">Customer Support</h2>
                    <p class="text-white/80 text-sm">Admin akan segera membalas</p>
                </div>
            </div>
            <a href="{{ url()->previous() }}" class="text-white hover:bg-white/20 p-2 rounded-lg transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <!-- Chat Messages -->
        <div id="chatMessages" class="flex-1 px-6 py-4 overflow-y-auto bg-gray-50">
            @if($messages->isEmpty())
                <div class="flex flex-col items-center justify-center h-full text-gray-400">
                    <svg class="w-20 h-20 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-lg font-medium">Mulai percakapan</p>
                    <p class="text-sm">Kirim pesan pertama Anda</p>
                </div>
            @else
                @foreach($messages as $message)
                    @php
                        $isAdmin = $message->sender_id !== Auth::id();
                    @endphp
                    <div class="mb-4 flex {{ $isAdmin ? 'justify-start' : 'justify-end' }}" data-message-id="{{ $message->id }}">
                        <div class="max-w-[70%]">
                            @if($isAdmin)
                                <div class="flex items-start gap-2">
                                    <div class="w-8 h-8 rounded-full bg-amber-600 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
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

        <!-- Chat Input -->
        <div class="px-6 py-4 bg-white border-t border-gray-200 flex-shrink-0">
            <form id="chatForm" class="flex gap-3">
                @csrf
                <input type="hidden" id="chatId" value="{{ $chat->id ?? '' }}">
                <input 
                    type="text" 
                    id="messageInput" 
                    placeholder="Ketik pesan Anda..."
                    class="flex-1 px-4 py-3 bg-gray-100 border-0 rounded-full focus:outline-none focus:ring-2 focus:ring-amber-500"
                    required
                >
                <button 
                    type="submit"
                    class="px-6 py-3 bg-gradient-to-r from-amber-600 to-orange-600 text-white rounded-full font-semibold hover:shadow-lg transition-all flex items-center gap-2 flex-shrink-0"
                >
                    <span>Kirim</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let chatId = document.getElementById('chatId').value;
let lastMessageId = {{ $messages->last()->id ?? 0 }};
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
    messageDiv.className = `mb-4 flex ${isAdmin ? 'justify-start' : 'justify-end'}`;
    messageDiv.setAttribute('data-message-id', message.id);
    
    if (isAdmin) {
        messageDiv.innerHTML = `
            <div class="max-w-[70%]">
                <div class="flex items-start gap-2">
                    <div class="w-8 h-8 rounded-full bg-amber-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
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
    
    // Create or get chat if not exists
    if (!chatId) {
        try {
            const response = await fetch('{{ route("customer.chat.getOrCreate") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ subject: 'Customer Support' })
            });
            
            const data = await response.json();
            chatId = data.chat_id;
            document.getElementById('chatId').value = chatId;
            startPolling();
        } catch (error) {
            console.error('Error creating chat:', error);
            return;
        }
    }
    
    // Send message
    try {
        const response = await fetch('{{ route("customer.chat.send") }}', {
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
            addMessage(data.message, false);
            messageInput.value = '';
        }
    } catch (error) {
        console.error('Error sending message:', error);
    }
});

// Poll for new messages
async function pollMessages() {
    if (!chatId) return;
    
    try {
        const response = await fetch(`{{ url('/customer/chat') }}/${chatId}/messages?last_message_id=${lastMessageId}`);
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
function startPolling() {
    if (!pollingInterval) {
        pollingInterval = setInterval(pollMessages, 3000);
    }
}

// Initialize
if (chatId) {
    scrollToBottom();
    startPolling();
}
</script>
@endsection
