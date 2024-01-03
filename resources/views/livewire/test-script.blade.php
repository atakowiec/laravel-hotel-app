<div>
    <input type="checkbox" wire:click="setChecked('first', $event.target.checked)">
    <input type="checkbox" wire:click="setChecked('second', $event.target.checked)">
    <input type="checkbox" wire:click="setChecked('third', $event.target.checked)">
    <input type="checkbox" wire:click="setChecked('fourth', $event.target.checked)">

    {{ json_encode($checked) }}
</div>
