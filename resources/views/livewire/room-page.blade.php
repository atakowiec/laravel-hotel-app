@section("title", $room->name)

@push("css")
    @vite("resources/sass/room.scss")
@endpush

@php($valid = $this->getErrorBag()->isEmpty())

<div class="row col-12 col-md-10 col-xxl-8 mx-auto room-box">
    <div class="col-12 info-box">
        <div class="info">
            <h3>
                {{ $room->name }}
                <span class="id">#{{ $room->id }}</span>
            </h3>
            <x-rating-stars :room_id="$room->id"/>
            <div class="details">
                {{$room->capacity}}-osobowy, {{$room->area}}m<sup>2</sup>,
                x={{$room->x_pos}}, z={{$room->z_pos}} ({{round($room->distance)}}m. od wejscia)
            </div>
        </div>
        <div class="row">
            <div class="col-9 me-0">
                <img class="room-image" src="{{asset("images/room.jpg")}}" alt="room"/>
            </div>
            <div class="col-3 book-box">
                <div class="box">
                    <h4>Zarezerwuj</h4>
                    <label>
                        <span>Od</span>
                        <input type="date" name="date-from" wire:model="dateFrom">
                    </label>
                    @error("dateFrom")
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <label>
                        <span>Do</span>
                        <input type="date" name="date-to" wire:model="dateTo">
                    </label>
                    @error("dateTo")
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <div class="price">
                        <span class="value">{{ $room->price }} zł / noc</span>
                    </div>
                </div>
                <div class="book-button">
                    <button @if(!$valid || !$available) disabled @else wire:click="showFloatingComponent('book-room')"
                            @endif class="submit">
                        <span wire:loading.class.remove="d-none" class="d-none d-flex">
                            <x-loading-animation size="sm"/>
                        </span>
                        <span wire:loading.remove>
                        @if($valid)
                                @if($available)
                                    Zarezerwuj za {{ $totalPrice }} zł
                                @else
                                    Termin zajęty
                                @endif
                            @else
                                Zarezerwuj
                            @endif
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <h4>Udogodnienia</h4>
    <div class="col-12 tags-box">
        @foreach($tags as $tag)
            <div class="tag">{{ $tag }}</div>
        @endforeach
    </div>
    <x-floating-container id="book-room">
        <h1>
            Potwierdzenie rezerwacji
        </h1>
        <div class="confirmation-values-box">
            <div class="key">
                Data zameldowania: <b>{{ $dateFrom }}</b>
            </div>
            <div class="key">
                Data wymeldowania: <b>{{ $dateTo }}</b>
            </div>
            <div class="key">
                Kwota: <b>{{ $days }} dni * {{ $room->price }}zł = {{ $totalPrice }}zł</b>
            </div>
            <div class="text-center mt-3">
                <button wire:click="bookRoom">
                    Zarezerwuj
                </button>
            </div>
        </div>
    </x-floating-container>
</div>
