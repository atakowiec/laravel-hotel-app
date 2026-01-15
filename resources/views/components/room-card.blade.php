@props(["room", "admin" => false])

@php
    use App\Models\Room;

    $reservationMessage = match ($room->reservations) {
        1 => "rezerwacja",
        2, 3, 4 => "rezerwacje",
        default => "rezerwacji"
    };
@endphp

<div class="room-card">
    @if(!$room->available && !$admin)
        <div class="not-available-overlay">
            <div class="not-available-text">
                <h3>Niedostępny</h3>
                <p>W tym terminie pokój jest już zarezerwowany</p>
            </div>
        </div>
    @endif
    <div class="image">
        <img src="{{ asset("storage/rooms/$room->photo") }}" alt="room">
    </div>
    <div class="info">
        <div class="info-content">
            <div>
                <div>
                    <a href="/room/{{ $room->id }}" style="color:black;text-decoration:none;">{{ $room->name }}</a>
                    <span class="room-id" style="color: #aaa;">#{{ $room->id }}</span>
                </div>
                <div class="capacity">
                    <span>Dla {{ $room->capacity }} {{ $room->capacity == 1 ? "osoby" : "osób" }},</span>
                </div>
                <div class="location">
                    <span>{{ $room->area }}m<sup>2</sup></span>,
                    Lokalizacja: x={{ $room->x_pos }}, y={{ $room->y_pos }}, z={{ $room->z_pos }} ({{ number_format($room->distance) }}m. od
                    wejścia), Ocena. 4.36
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
        @if($admin)
            <div class="price">
                <a href="/edit-room?roomId={{ $room->id }}">
                    <div class="button">
                        Edytuj
                    </div>
                </a>
                <div class="button delete" wire:click="showFloatingComponent('deleteRoom', {{$room->id}})">
                    Usuń
                </div>
                <span>{{ number_format($room->price, 2) }} zł / noc</span>
            </div>
        @else
            <div class="price">
                <a href="/room/{{ $room->id }}">
                    <div class="button">
                        Szczególy
                    </div>
                </a>
                <span>{{ number_format($room->price, 2) }} <span style="font-weight: normal; font-size: 0.8rem; color: #999;">zł / noc</span></span>
                <span class="reservation-count">
                {{ $room->reservations }} {{ $reservationMessage }}
                </span>
            </div>
        @endif
    </div>
</div>
