<div class="mail-app">
    <!-- Sidebar -->
    <div class="mail-sidebar {{ $selectedMessage ? 'hidden-mobile' : '' }}">
        <div class="sidebar-header">
            <h2>✉️ Mail</h2>
            <button wire:click="$dispatch('open-compose')" class="compose-btn">+ Compose</button>
        </div>

        <div class="folder-list">
            <a wire:click="$set('folder', 'inbox')" class="{{ $folder === 'inbox' ? 'active' : '' }}">
                📥 Inbox @if($unreadCount)<span class="badge">{{ $unreadCount }}</span>@endif
            </a>
            <a wire:click="$set('folder', 'sent')" class="{{ $folder === 'sent' ? 'active' : '' }}">📤 Sent</a>
            <a wire:click="$set('folder', 'starred')" class="{{ $folder === 'starred' ? 'active' : '' }}">⭐ Starred</a>
            <a wire:click="$set('folder', 'trash')" class="{{ $folder === 'trash' ? 'active' : '' }}">🗑️ Trash</a>
        </div>

        <div class="search-box">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search mail...">
        </div>

        <div class="message-list">
            @forelse($messages as $msg)
            <div wire:click="selectMessage({{ $msg->id }})" class="msg-item {{ !$msg->is_read && $msg->receiver_id === auth()->id() ? 'unread' : '' }} {{ $selectedMessage === $msg->id ? 'selected' : '' }}">
                <div class="msg-avatar">{{ strtoupper(substr($folder === 'sent' ? $msg->receiver->name : $msg->sender->name, 0, 1)) }}</div>
                <div class="msg-preview">
                    <div class="msg-from">{{ $folder === 'sent' ? $msg->receiver->name : $msg->sender->name }}</div>
                    <div class="msg-subject">{{ Str::limit($msg->subject, 30) }}</div>
                    <div class="msg-snippet">{{ Str::limit($msg->body, 40) }}</div>
                </div>
                <div class="msg-meta">
                    <span class="msg-time">{{ $msg->created_at->format('H:i') }}</span>
                    @if($msg->is_starred)<span class="star">⭐</span>@endif
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-icon">📭</div>
                <p>No messages in {{ $folder }}</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Message Detail -->
    @if($selected)
    <div class="mail-detail">
        <div class="detail-header">
            <button wire:click="backToList" class="back-btn">← Back</button>
            <div class="detail-actions">
                <button wire:click="toggleStar({{ $selected->id }})" title="Star">{{ $selected->is_starred ? '⭐' : '☆' }}</button>
                <button wire:click="$dispatch('open-compose', { replyTo: {{ $selected->id }} })" title="Reply">↩️</button>
                <button wire:click="trash({{ $selected->id }})" title="Delete" class="danger">🗑️</button>
            </div>
        </div>
        <div class="detail-body">
            <h3>{{ $selected->subject }}</h3>
            <div class="detail-meta">
                <strong>{{ $selected->sender->name }}</strong> → {{ $selected->receiver->name }}
                <span class="detail-time">{{ $selected->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="detail-content">{!! nl2br(e($selected->body)) !!}</div>
        </div>
    </div>
    @else
    <div class="mail-detail empty-detail {{ !$selectedMessage ? 'hidden-mobile' : '' }}">
        <div class="empty-state">
            <div class="empty-icon">📧</div>
            <p>Select a message to read</p>
        </div>
    </div>
    @endif
</div>
