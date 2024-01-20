@php($future = strtotime($reservation->date_from) > strtotime(date('Y-m-d')))
@php($past = strtotime($reservation->date_to) < strtotime(date('Y-m-d')) && !$reservation->cancelled)
@php($reviewed = $reservation->getUserReview() != null)
@php($review = $reservation->getUserReview())

<div class="reservation-card col-12 row">
    <div class="room-image col-3">
        <img src="{{ asset("storage/rooms/{$reservation->room->photo}") }}" alt="room">
    </div>
    <div class="info col-6">
        <a href="{{route('room', ['id' => $reservation->room->id])}}">
            <h5>
                {{$reservation->room->name}}
                <span class="id">#{{$reservation->room->id}}</span>
            </h5>
        </a>
        <x-rating-stars :room_id="$reservation->room_id"/>
        <div class="date">
            {{$reservation->date_from}} - {{$reservation->date_to}}
        </div>
        <div class="price">
            Suma: {{$reservation->total_cost}} zł
        </div>
    </div>
    <div class="right-box col-3">
        @if($reservation->cancelled)
            Anulowano
        @elseif($future)
            <button class="cancel" wire:click="showFloatingComponent('cancel_reservation', {{$reservation->id}})">
                Anuluj
            </button>
        @endif

        @if($past && !$reviewed)
            <button class="accept" wire:click="showFloatingComponent('review', {{$reservation->id}})">
                Dodaj opinie
            </button>
        @endif
        @if($reviewed)
            Opinia dodana
        @endif
    </div>
</div>
