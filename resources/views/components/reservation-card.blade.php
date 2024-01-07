@php($future = strtotime($reservation->date_from) > strtotime(date('Y-m-d')))

<div class="reservation-card col-12 row">
    <div class="room-image col-3">
        <img src="{{ asset("images/room.jpg") }}" alt="room">
    </div>
    <div class="info col-6">
        <h5>
            {{$reservation->room->name}}
            <span class="id">#{{$reservation->room->id}}</span>
        </h5>
        <x-rating-stars :room_id="$reservation->room_id"/>
        <div class="date">
            {{$reservation->date_from}} - {{$reservation->date_to}}
        </div>
        <div class="price">
            Suma: {{$reservation->total_cost}} zł
        </div>
    </div>
    <div class="right-box col-3">
        @if($future)
            <button class="cancel" wire:click="cancel_reservation({{$reservation->id}})">
                Anuluj
            </button>
        @endif
        @if($reservation->cancelled)
            Anulowano
        @endif
    </div>
</div>
