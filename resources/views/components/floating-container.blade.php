@props(['id' => Str::random(10), "class" => ''])

<div id="{{$id}}"
     class="floating-container @if($this->isVisible($id)) show @endif"
     wire:click="hideFloatingComponent('{{$id}}')">
    <div class="content col-12 col-md-6 col-xxl-4 {{$class}}">
        {{$slot}}
    </div>
</div>

@push('other-scripts')
    <script>
        document.querySelector('#{{$id}} .content')
            .addEventListener('click', e => e.stopPropagation());
    </script>
@endpush
