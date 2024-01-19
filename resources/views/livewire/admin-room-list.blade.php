@push("css")
    @vite("resources/sass/main.scss")
    @vite("resources/sass/admin.scss")
@endpush

@section("title", "Panel admina")

<div class="admin-rooms-list col-12 col-md-8 col-xxl-6 mx-auto">
    <div class="search-box row">
        <label for="search" class="col-12 col-md-7">
            <input id="search" placeholder="Nazwa pokoju" type="search" wire:model.debounce.200ms="name"/>
        </label>
        <label for="capacity" class="col-3 mx-auto">
            <select wire:model="capacity" id="capacity">
                <option value="-1">-</option>
                @foreach($capacities as $capacity)
                    <option value="{{ $capacity }}">{{ $capacity }}-osobowy</option>
                @endforeach
            </select>
        </label>
        <div class="col-3 col-md-2 add-room">
            <a href="/edit-room">
                <button>
                    Dodaj pokój
                </button>
            </a>
        </div>
    </div>
    <div class="rooms-list">
        @if($rooms->isEmpty())
            <div class="no-data-box">
                <h3>Brak wyników wyszukiwania</h3>
                <p>Spróbuj zmienić kryteria wyszukiwania</p>
            </div>
        @else
            @foreach($rooms as $room)
                <div class="room">
                    <x-room-card :room="$room" admin="true"/>
                </div>
            @endforeach
        @endif
    </div>

    <x-floating-confirmation
        id="deleteRoom"
        title="Usuniecie pokoju"
        message="Czy na pewno chcesz ten pokój? Wszystkie jego dane zostaną bezpowrotnie usunięte!"
        acceptText="Usuń pokój"
        cancelText="Nie, nie usuwaj"
    />
</div>
