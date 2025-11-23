<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    // Data dummy Daftar Chat (Panel Kiri)
    private $chatList = [
        ['id' => 1, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
        ['id' => 2, 'user_name' => 'Bambang', 'last_message' => 'Gimana cara order?', 'status' => 'new'],
        ['id' => 3, 'user_name' => 'Siti', 'last_message' => 'Konfirmasi pembayaran', 'status' => 'responded'],
        ['id' => 4, 'user_name' => 'Faisal', 'last_message' => 'Motif lain ada?', 'status' => 'new'],
        ['id' => 5, 'user_name' => 'Rina', 'last_message' => 'Tolong refund.', 'status' => 'closed'],
        // Duplikasi untuk mengisi daftar panjang di sidebar
        ['id' => 6, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
        ['id' => 7, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
        ['id' => 8, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
        ['id' => 9, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
        ['id' => 10, 'user_name' => 'Rosyadi', 'last_message' => 'Stok Ready min?', 'status' => 'new'],
    ];

    // Data dummy Isi Chat (Panel Kanan)
    // Anggap yang ditampilkan adalah chatID 1
    private $currentChatMessages = [
        ['id' => 101, 'sender' => 'user', 'message' => 'Stok Ready min?'],
        ['id' => 102, 'sender' => 'admin', 'message' => 'Ready mas.'],
        ['id' => 103, 'sender' => 'user', 'message' => 'Stok Ready min?'],
        ['id' => 104, 'sender' => 'admin', 'message' => 'Ready mas.'],
        ['id' => 105, 'sender' => 'user', 'message' => 'Stok Ready min?'],
        ['id' => 106, 'sender' => 'admin', 'message' => 'Ready mas.'],
        ['id' => 107, 'sender' => 'user', 'message' => 'Stok Ready min?'],
        ['id' => 108, 'sender' => 'admin', 'message' => 'Ready mas.'],
        ['id' => 109, 'sender' => 'user', 'message' => 'Stok Ready min?'],
        ['id' => 110, 'sender' => 'admin', 'message' => 'Ready mas.'],
    ];

    /**
     * Menampilkan halaman Live Chat Support.
     */
    public function index()
    {
        return view('admin.pages.liveChatSupport', [
            'chatList' => $this->chatList,
            'currentChatMessages' => $this->currentChatMessages,
            'currentChatName' => 'Rosyadi', // Default name untuk judul chat
            'activePage' => 'live-chat'
        ]);
    }
}