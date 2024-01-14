<?php

namespace App\Http\Livewire;

use Illuminate\View\View;
use Livewire\Component;

class FlashMessage extends Component
{
    public $messages = [];

    protected $listeners = ['flashMessage' => 'flashMessage'];

    public function flashMessage($message, $ttl = 5): void
    {
        $this->messages[] = [
            'id' => now()->timestamp,
            'message' => $message,
            'expires_at' => now()->addSeconds($ttl)->timestamp,
        ];
    }

    public function deleteFlashMessage($id): void
    {
        $this->messages = array_filter($this->messages, function ($message) use ($id) {
            return $message['id'] !== $id;
        });
    }

    public function checkFlashMessages(): void
    {
        $this->messages = array_filter($this->messages, function ($message) {
            return $message['expires_at'] > now()->timestamp;
        });
    }

    public function render(): View
    {
        return view('livewire.flash-message');
    }
}
