@props(["room"])

@php
    use App\Models\Room;

    $reservationMessage = match ($room->reservations) {
        1 => "rezerwacja",
        2, 3, 4 => "rezerwacje",
        default => "rezerwacji"
    };
@endphp

<div class="room-card">
    @if(!$room->available)
        <div class="not-available-overlay">
            <div class="not-available-text">
                <h3>Niedostępny</h3>
                <p>W tym terminie pokój jest już zarezerwowany</p>
            </div>
        </div>
    @endif
    <div class="image">
        <img src="{{ asset('images/room.jpg') }}" alt="room">
    </div>
    <div class="info">
        <div class="info-content">
            <div>
                <h3>
                    <a href="/room/{{ $room->id }}">{{ $room->name }}</a>
                    <span class="room-id">#{{ $room->id }}</span>
                </h3>
                <div>
                    <x-rating-stars room_id="{{ $room->id }}"/>
                </div>
                <div class="capacity">
                    <span>Dla {{ $room->capacity }} {{ $room->capacity == 1 ? "osoby" : "osób" }},</span>
                    <span>{{ $room->area }}m<sup>2</sup></span>
                </div>
                <div class="location">
                    Lokalizacja: x={{ $room->x_pos }}, y={{ $room->z_pos }} ({{ number_format($room->distance) }}m. od
                    wejścia)
                </div>
            </div>
            @php
                $tags = Room::getTags($room->id)
            @endphp
            @if( count($tags) > 0 )
                <div class="amenities">
                    <h5>Udogodnienia</h5>
                    <ul>
                        @foreach($tags as $tag)
                            <li>{{ $tag }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="price">
            <a href="/room/{{ $room->id }}">
                <div class="button">
                    Szczególy
                </div>
            </a>
            <span>{{ number_format($room->price, 2) }} zł / noc</span>
            <span class="reservation-count">
            {{ $room->reservations }} {{ $reservationMessage }}
            </span>
        </div>
    </div>
</div>
