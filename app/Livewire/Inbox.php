<?php

namespace App\Livewire;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Inbox extends Component
{
    public string $folder = 'inbox';
    public ?int $selectedMessage = null;
    public string $search = '';

    public function mount(string $folder = 'inbox')
    {
        $this->folder = $folder;
    }

    public function selectMessage(int $id)
    {
        $this->selectedMessage = $id;
        $message = Message::find($id);
        if ($message && $message->receiver_id === Auth::id() && !$message->is_read) {
            $message->update(['is_read' => true]);
        }
    }

    public function backToList()
    {
        $this->selectedMessage = null;
    }

    public function toggleStar(int $id)
    {
        $message = Message::find($id);
        if ($message) {
            $message->update(['is_starred' => !$message->is_starred]);
        }
    }

    public function trash(int $id)
    {
        $message = Message::find($id);
        if ($message) {
            if ($message->sender_id === Auth::id()) {
                $message->update(['is_trashed_sender' => true]);
            }
            if ($message->receiver_id === Auth::id()) {
                $message->update(['is_trashed_receiver' => true]);
            }
        }
        $this->selectedMessage = null;
    }

    public function getMessagesProperty()
    {
        $userId = Auth::id();
        $query = Message::query();

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('subject', 'like', "%{$this->search}%")
                  ->orWhere('body', 'like', "%{$this->search}%");
            });
        }

        return match ($this->folder) {
            'inbox' => $query->where('receiver_id', $userId)->where('is_trashed_receiver', false)->latest()->get(),
            'sent' => $query->where('sender_id', $userId)->where('is_trashed_sender', false)->latest()->get(),
            'starred' => $query->where(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)->orWhere('sender_id', $userId);
            })->where('is_starred', true)->latest()->get(),
            'trash' => $query->where(function ($q) use ($userId) {
                $q->where('receiver_id', $userId)->where('is_trashed_receiver', true)
                  ->orWhere(function ($q2) use ($userId) {
                      $q2->where('sender_id', $userId)->where('is_trashed_sender', true);
                  });
            })->latest()->get(),
            default => collect(),
        };
    }

    public function render()
    {
        return view('livewire.inbox', [
            'messages' => $this->messages,
            'selected' => $this->selectedMessage ? Message::with(['sender', 'receiver'])->find($this->selectedMessage) : null,
            'unreadCount' => Message::where('receiver_id', Auth::id())->where('is_read', false)->where('is_trashed_receiver', false)->count(),
        ]);
    }
}
