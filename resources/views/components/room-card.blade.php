@props(["room"])

@php
    use App\Models\Room;
@endphp

<div class="room-card">
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
                    <span>{{ $room->size }}m<sup>2</sup></span>
                </div>
                <div class="location">
                    Lokalizacja: x={{ $room->x_pos }}, y={{ $room->z_pos }} ({{ number_format($room->distance) }}m. od wejścia)
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
                <button role="button">
                    Szczególy
                </button>
            </a>
            <span>{{ number_format($room->price, 2) }} zł / noc</span>
        </div>
    </div>
</div>
