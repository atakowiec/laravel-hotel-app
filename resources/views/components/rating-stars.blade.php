@props(['room_id'])
@php
    use App\Models\Room;
    $ratingData = Room::getRating($room_id);
    $rating = $ratingData['average'];
@endphp

<div class="rating-stars">
    @for($i = 0; $i < 5; $i++)
        @if($rating >= 1)
            <img src="{{asset("images/full-star.png")}}" alt="star">
        @elseif($rating >= 0.5)
            <img src="{{asset("images/half-star.png")}}" alt="half star">
        @else
            <img src="{{asset("images/empty-star.png")}}" alt="empty star">
        @endif
        @php
            $rating--;
        @endphp
    @endfor
    <span>{{ number_format($ratingData['average'], 1) }} ({{ $ratingData["count"] }} opinii)</span>
</div>
