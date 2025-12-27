<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Halaman Live Chat Customer
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create active chat untuk user ini
        $chat = Chat::where('user_id', $user->id)
            ->where('status', 'active')
            ->with(['messages.sender', 'admin'])
            ->first();

        return view('customer.pages.liveChat', [
            'chat' => $chat,
            'messages' => $chat ? $chat->messages : collect([]),
        ]);
    }

    /**
     * Get atau Create Chat Room
     */
    public function getOrCreateChat(Request $request)
    {
        $user = Auth::user();
        
        // Cek apakah user sudah punya active chat
        $chat = Chat::where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (!$chat) {
            // Buat chat baru
            $chat = Chat::create([
                'user_id' => $user->id,
                'subject' => $request->subject ?? 'Customer Support',
                'status' => 'active',
                'last_message_at' => now(),
            ]);
        }

        return response()->json([
            'success' => true,
            'chat_id' => $chat->id,
        ]);
    }

    /**
     * Kirim Pesan
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'chat_id' => 'required|exists:chats,id',
            'message' => 'required|string|max:1000',
        ]);

        $user = Auth::user();
        $chat = Chat::findOrFail($request->chat_id);

        // Pastikan chat ini milik user yang sedang login
        if ($chat->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Simpan message
        $message = ChatMessage::create([
            'chat_id' => $chat->id,
            'sender_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Update last_message_at di chat
        $chat->update(['last_message_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'created_at' => $message->created_at->format('H:i'),
                'sender_name' => $user->name,
                'is_admin' => false,
            ],
        ]);
    }

    /**
     * Get New Messages (Polling)
     */
    public function getNewMessages(Request $request, $chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        // Pastikan chat ini milik user yang sedang login
        if ($chat->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $lastMessageId = $request->query('last_message_id', 0);

        $newMessages = ChatMessage::where('chat_id', $chatId)
            ->where('id', '>', $lastMessageId)
            ->with('sender')
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark admin messages as read
        ChatMessage::where('chat_id', $chatId)
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'messages' => $newMessages->map(function ($msg) use ($user) {
                return [
                    'id' => $msg->id,
                    'message' => $msg->message,
                    'created_at' => $msg->created_at->format('H:i'),
                    'sender_name' => $msg->sender->name,
                    'is_admin' => $msg->sender_id !== $user->id,
                ];
            }),
        ]);
    }

    /**
     * Close Chat
     */
    public function closeChat($chatId)
    {
        $user = Auth::user();
        $chat = Chat::findOrFail($chatId);

        if ($chat->user_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $chat->update(['status' => 'closed']);

        return response()->json(['success' => true]);
    }
}
