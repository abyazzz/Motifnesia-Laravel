<div class="notification-item {{ !$notification->is_read ? 'unread' : '' }}" data-priority="{{ $notification->priority }}">
    <div class="notification-content">
        <!-- Icon & Badge -->
        <div class="notification-icon-wrapper">
            <span class="notification-icon">{{ $notification->icon }}</span>
            @if(!$notification->is_read)
                <span class="unread-badge"></span>
            @endif
        </div>

        <!-- Main Content -->
        <div class="notification-main">
            <div class="notification-header">
                <h3 class="notification-title">{{ $notification->title }}</h3>
                <div class="notification-badges">
                    <span class="badge badge-{{ $notification->priority_color }}">
                        {{ ucfirst($notification->priority) }}
                    </span>
                    <span class="badge badge-type">{{ ucfirst($notification->type) }}</span>
                </div>
            </div>
            
            <p class="notification-message">{{ $notification->message }}</p>
            
            <div class="notification-footer">
                <span class="notification-time">{{ $notification->time_ago }}</span>
                @if($notification->link)
                    <a href="{{ $notification->link }}" class="notification-link">
                        Lihat Detail →
                    </a>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="notification-actions">
            <button 
                class="btn-icon" 
                onclick="toggleRead({{ $notification->id }})"
                title="{{ $notification->is_read ? 'Tandai belum dibaca' : 'Tandai sudah dibaca' }}">
                @if($notification->is_read)
                    📭
                @else
                    ✓
                @endif
            </button>
            <button 
                class="btn-icon btn-delete" 
                onclick="deleteNotification({{ $notification->id }})"
                title="Hapus notifikasi">
                🗑️
            </button>
        </div>
    </div>

    <!-- Priority Bar -->
    <div class="priority-bar priority-{{ $notification->priority_color }}"></div>
</div>
