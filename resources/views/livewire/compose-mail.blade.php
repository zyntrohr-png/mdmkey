<div>
    @if($showCompose)
    <div class="compose-overlay" wire:click.self="close">
        <div class="compose-modal">
            <div class="compose-header">
                <h3>New Message</h3>
                <button wire:click="close" class="close-btn">✕</button>
            </div>
            <form wire:submit="send">
                <div class="compose-field">
                    <label>To</label>
                    <input type="email" wire:model="to" placeholder="recipient@email.com" list="user-list" required>
                    <datalist id="user-list">
                        @foreach($users as $user)
                        <option value="{{ $user->email }}">{{ $user->name }}</option>
                        @endforeach
                    </datalist>
                    @error('to') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="compose-field">
                    <label>Subject</label>
                    <input type="text" wire:model="subject" placeholder="Subject" required>
                    @error('subject') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="compose-field">
                    <label>Message</label>
                    <textarea wire:model="body" placeholder="Write your message..." rows="8" required></textarea>
                    @error('body') <span class="error">{{ $message }}</span> @enderror
                </div>
                <div class="compose-actions">
                    <button type="submit" class="send-btn">📤 Send</button>
                    <button type="button" wire:click="close" class="discard-btn">Discard</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
