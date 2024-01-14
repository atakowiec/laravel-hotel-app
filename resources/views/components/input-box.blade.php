@props(['id' , 'label', 'type' => 'text', 'class'=>'', 'step' => 'any'])

<div class="input-box {{ $class }}">
    <label for="{{ $id }}">{{ $label }}</label>
    <input type="{{$type}}" id="{{ $id }}" step="{{ $step }}"
           wire:model.debounce.200ms="{{ $id }}" {{$this->getErrorClass($id)}}>
    @error($id)
    <div class="error">
        {{ $message }}
    </div>
    @enderror
</div>
