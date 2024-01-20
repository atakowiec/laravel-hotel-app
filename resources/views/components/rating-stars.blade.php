@props(['room_id' => null, 'rating' => null])
@php
    use App\Models\Room;

    $rating = isset($room_id) ? Room::getRating($room_id)['average'] : $rating;
    $ratingClone = $rating;
@endphp

<div class="rating-stars">
    @for($i = 0; $i < 5; $i++)
        @if($ratingClone >= 1)
            <img src="{{asset("images/full-star.png")}}" alt="star">
        @elseif($ratingClone >= 0.5)
            <img src="{{asset("images/half-star.png")}}" alt="half star">
        @else
            <img src="{{asset("images/empty-star.png")}}" alt="empty star">
        @endif
        @php
            $ratingClone--;
        @endphp
    @endfor
    @if(isset($room_id))
        @php($ratingData = Room::getRating($room_id))
        <span>{{ number_format($rating, 1) }} ({{ $ratingData["count"] }} opinii)</span>
    @endif
</div>
