<x-app-layout>
    <style>
        .mail-app { display: flex; height: calc(100vh - 65px); background: #0f172a; }
        .mail-sidebar { width: 350px; border-right: 1px solid #1e293b; display: flex; flex-direction: column; background: #0f172a; }
        .sidebar-header { padding: 16px 20px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #1e293b; }
        .sidebar-header h2 { font-size: 18px; color: #f1f5f9; }
        .compose-btn { padding: 8px 16px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border: none; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; }
        .compose-btn:hover { opacity: 0.9; }
        .folder-list { padding: 12px 10px; border-bottom: 1px solid #1e293b; }
        .folder-list a { display: flex; align-items: center; padding: 10px 14px; border-radius: 10px; color: #94a3b8; font-size: 13px; cursor: pointer; margin-bottom: 2px; transition: all 0.15s; }
        .folder-list a:hover { background: #1e293b; color: #f1f5f9; }
        .folder-list a.active { background: #1e293b; color: #6366f1; font-weight: 600; }
        .folder-list .badge { margin-left: auto; background: #6366f1; color: #fff; font-size: 10px; padding: 2px 8px; border-radius: 10px; }
        .search-box { padding: 10px 14px; }
        .search-box input { width: 100%; padding: 10px 14px; background: #1e293b; border: 1px solid #334155; border-radius: 10px; color: #e2e8f0; font-size: 13px; }
        .search-box input:focus { outline: none; border-color: #6366f1; }
        .message-list { flex: 1; overflow-y: auto; }
        .msg-item { display: flex; padding: 14px 16px; cursor: pointer; border-bottom: 1px solid #1e293b; gap: 12px; transition: background 0.15s; }
        .msg-item:hover { background: #1e293b; }
        .msg-item.selected { background: #1e293b; border-left: 3px solid #6366f1; }
        .msg-item.unread { background: rgba(99,102,241,0.05); }
        .msg-item.unread .msg-from { color: #f1f5f9; font-weight: 700; }
        .msg-avatar { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #8b5cf6); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 14px; flex-shrink: 0; }
        .msg-preview { flex: 1; min-width: 0; }
        .msg-from { font-size: 13px; color: #e2e8f0; font-weight: 500; }
        .msg-subject { font-size: 12px; color: #94a3b8; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .msg-snippet { font-size: 11px; color: #64748b; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .msg-meta { display: flex; flex-direction: column; align-items: end; gap: 4px; }
        .msg-time { font-size: 10px; color: #64748b; }
        .star { font-size: 12px; }
        /* Detail */
        .mail-detail { flex: 1; display: flex; flex-direction: column; background: #0f172a; }
        .detail-header { padding: 16px 20px; border-bottom: 1px solid #1e293b; display: flex; align-items: center; justify-content: space-between; }
        .back-btn { background: none; border: none; color: #6366f1; font-size: 14px; cursor: pointer; }
        .detail-actions button { background: none; border: none; font-size: 18px; cursor: pointer; margin-left: 12px; }
        .detail-actions .danger { color: #f87171; }
        .detail-body { padding: 24px; flex: 1; overflow-y: auto; }
        .detail-body h3 { font-size: 20px; color: #f1f5f9; margin-bottom: 12px; }
        .detail-meta { font-size: 12px; color: #94a3b8; margin-bottom: 20px; }
        .detail-time { margin-left: 12px; color: #64748b; }
        .detail-content { font-size: 14px; color: #cbd5e1; line-height: 1.7; }
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px; color: #64748b; }
        .empty-icon { font-size: 48px; margin-bottom: 12px; }
        .empty-detail { display: flex; align-items: center; justify-content: center; }
        /* Compose */
        .compose-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.6); display: flex; align-items: center; justify-content: center; z-index: 50; padding: 20px; }
        .compose-modal { background: #1e293b; border-radius: 16px; width: 100%; max-width: 500px; border: 1px solid #334155; }
        .compose-header { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-bottom: 1px solid #334155; }
        .compose-header h3 { color: #f1f5f9; font-size: 16px; }
        .close-btn { background: none; border: none; color: #94a3b8; font-size: 18px; cursor: pointer; }
        .compose-field { padding: 10px 20px; }
        .compose-field label { display: block; font-size: 11px; color: #64748b; text-transform: uppercase; margin-bottom: 4px; }
        .compose-field input, .compose-field textarea { width: 100%; padding: 10px 14px; background: #0f172a; border: 1px solid #334155; border-radius: 8px; color: #e2e8f0; font-size: 13px; }
        .compose-field textarea { resize: vertical; min-height: 120px; }
        .compose-field input:focus, .compose-field textarea:focus { outline: none; border-color: #6366f1; }
        .compose-field .error { color: #f87171; font-size: 11px; margin-top: 4px; display: block; }
        .compose-actions { padding: 16px 20px; display: flex; gap: 10px; border-top: 1px solid #334155; }
        .send-btn { padding: 10px 24px; background: linear-gradient(135deg, #6366f1, #8b5cf6); color: #fff; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; }
        .discard-btn { padding: 10px 20px; background: transparent; border: 1px solid #334155; color: #94a3b8; border-radius: 10px; cursor: pointer; }
        /* Mobile */
        @media (max-width: 768px) {
            .mail-app { flex-direction: column; }
            .mail-sidebar { width: 100%; height: 100%; position: absolute; z-index: 10; }
            .mail-detail { width: 100%; height: 100%; position: absolute; z-index: 20; }
            .hidden-mobile { display: none !important; }
            .empty-detail { display: none; }
        }
    </style>

    @if(session('success'))
    <div style="position:fixed;top:70px;right:20px;padding:10px 20px;background:rgba(74,222,128,0.15);color:#4ade80;border-radius:8px;font-size:13px;z-index:100;">
        {{ session('success') }}
    </div>
    @endif

    <livewire:inbox />
    <livewire:compose-mail />

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('open-compose', (data) => {
                Livewire.dispatch('open', data);
            });
        });
    </script>
</x-app-layout>
