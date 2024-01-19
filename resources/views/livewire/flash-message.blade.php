<div class="flash-messages-box" wire:poll.1000ms="checkFlashMessages">
    @foreach($messages as $message)
        <div class="flash-message col-11 col-md-3 col-xxl-2" wire:click="deleteFlashMessage({{ $message['id'] }})">
            {{ $message['message'] }}
        </div>
    @endforeach
</div>
