@props(['id' => Str::random(10), "class" => ''])

<div id="{{$id}}" class="floating-container">
    <div class="content col-12 col-md-6 col-xxl-4 {{$class}}">
        {{$slot}}
    </div>
</div>

@push('other-scripts')
    <script>
        let ID = '{{$id}}';
        let element = document.getElementById('{{$id}}');
        let content = element.querySelector('#{{$id}} .content');

        element.addEventListener('click', e => hideFloatingComponent(e));
        content.addEventListener('click', e => e.stopPropagation());

        function hideFloatingComponent(event, id = ID) {
            event.stopPropagation();
            if (id !== ID)
                return;

            element.classList.remove('show');
        }

        function showFloatingContainer(id) {
            if (id !== ID)
                return;

            element.classList.add('show');
        }
    </script>
@endpush
