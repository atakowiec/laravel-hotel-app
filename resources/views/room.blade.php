@extends('layout')

@section("title", $room->name)

@php($tags = \App\Models\Room::getTags($room->id))

@push("css")
    @vite("resources/sass/room.scss")
@endpush

@section("content")
    <div class="row col-12 col-md-10 col-xxl-8 mx-auto room-box">
        <div class="col-12 info-box">
            <div class="info">
                <h3>
                    {{ $room->name }}
                    <span class="id">#{{ $room->id }}</span>
                </h3>
                <x-rating-stars :room_id="$room->id"/>
            </div>
            <div class="row">
                <div class="col-9 me-0">
                    <img class="room-image" src="{{asset("images/room.jpg")}}" alt="room"/>
                </div>
                <div class="col-3 book-box">
                    <h4>Zarezerwuj</h4>
                    <div class="box">
                        <label>
                            <span>Od</span>
                            <input type="date" name="date-from" value="">
                        </label>
                        <label>
                            <span>Do</span>
                            <input type="date" name="date-to" value="">
                        </label>
                        <div class="price">
                            <span class="value">{{ $room->price }} zł / noc</span>
                        </div>
                        <button class="submit">Zarezerwuj za {{ $room->price }} zł</button>
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
    </div>
    @livewire('test-script')
@endsection
