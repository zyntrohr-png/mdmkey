<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class ComposeMail extends Component
{
    public string $to = '';
    public string $subject = '';
    public string $body = '';
    public bool $showCompose = false;
    public ?int $replyTo = null;

    #[On('open-compose')]
    public function open(?int $replyTo = null)
    {
        $this->showCompose = true;
        $this->replyTo = $replyTo;

        if ($replyTo) {
            $original = Message::find($replyTo);
            if ($original) {
                $this->to = $original->sender_id === Auth::id()
                    ? $original->receiver->email
                    : $original->sender->email;
                $this->subject = 'Re: ' . $original->subject;
            }
        }
    }

    public function close()
    {
        $this->reset(['to', 'subject', 'body', 'showCompose', 'replyTo']);
    }

    public function send()
    {
        $this->validate([
            'to' => 'required|email|exists:users,email',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
        ]);

        $receiver = User::where('email', $this->to)->first();

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'subject' => $this->subject,
            'body' => $this->body,
        ]);

        $this->close();
        $this->dispatch('message-sent');

        session()->flash('success', 'Message sent!');
    }

    public function render()
    {
        $users = User::where('id', '!=', Auth::id())->get(['id', 'name', 'email']);
        return view('livewire.compose-mail', compact('users'));
    }
}
