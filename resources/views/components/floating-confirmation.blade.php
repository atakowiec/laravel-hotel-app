@props(['id',
         'title',
         'message' => "",
         'acceptText' => "Akceptuj",
         'cancelText' => "Anuluj"])

<x-floating-container id="{{ $id }}" class="floating-confirmation">
    <h1>
        {{$title}}
    </h1>
    @if(!empty($message))
        <div class="message">
            {{$message}}
        </div>
    @endif
    <div class="buttons">
        <button class="accept" wire:click="confirm('{{ $id }}')">
            {{ $acceptText }}
        </button>
        <button class="cancel" wire:click="cancel('{{ $id }}')">
            {{$cancelText}}
        </button>
    </div>
</x-floating-container>
