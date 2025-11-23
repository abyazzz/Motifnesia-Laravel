@extends('admin.layouts.mainLayout')

@section('title', 'Live Chat Support')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/liveChatSupport.css') }}">

    <div class="live-chat-container">
        <header class="chat-header">
            <h1 class="header-title">Live Chat Support</h1>
            <div class="search-box">
                <input type="text" placeholder="Cari..." class="search-input">
                <button class="search-button" type="button">🔍</button>
            </div>
        </header>

        <main class="chat-main-panel">
            {{-- PANEL KIRI: DAFTAR CHAT --}}
            <aside class="chat-list-panel">
                @foreach($chatList as $chat)
                    @include('admin.components.chatListItem', ['chat' => $chat])
                @endforeach
            </aside>

            {{-- PANEL KANAN: ISI CHAT --}}
            <section class="chat-content-panel">
                <h2 class="chat-content-title">Live Chat Support</h2> {{-- Sesuai Judul di Screenshot --}}
                
                <div class="chat-messages-area">
                    @foreach($currentChatMessages as $message)
                        @include('admin.components.chatBubble', ['message' => $message])
                    @endforeach
                </div>

                {{-- Input Chat (di-skip visualnya karena fokus ke tampilan data, tapi siapkan div-nya) --}}
                {{-- <div class="chat-input-area">
                    <input type="text" placeholder="Ketik pesan..." class="message-input">
                    <button class="send-button">Kirim</button>
                </div> --}}
            </section>
        </main>
    </div>
@endsection