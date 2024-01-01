@php
    use App\Models\Room;
    use App\Models\RoomTags;
    use App\Models\AvailableTags;

    $rooms = Room::all();

    $max_price = ceil(Room::max('price'));
    $min_price = floor(Room::min('price'));

    $tags = AvailableTags::all();
    $tagsCounts = RoomTags::getTagsCount($rooms);

    $tags = $tags->map(function ($tag) use ($tagsCounts) {
        $tag->count = $tagsCounts[$tag->name] ?? 0;
        return $tag;
    });

    $tags = $tags->sortByDesc('count');

    $tags = $tags->values()->all();
@endphp
@section('title', "Strona głowna")

@vite(["/resources/sass/main.scss"])

{{--todo filter based on get data--}}

@extends('layout')

@section('content')
    <script>
        function show() {
            let toShow = document.getElementById("rest-to-show");
            let button = document.getElementById("button-to-hide");
            button.classList.add("d-none");
            toShow.classList.remove("d-none");
        }
    </script>
    <div class="main row mx-auto col-12 col-md-9">
        <form class="col-3 filters">
            <h3>Termin</h3>
            <div class="box">
                <label>
                    <span>Od</span>
                    <input type="date" name="date-from">
                </label>
                <label>
                    <span>Do</span>
                    <input type="date" name="date-to">
                </label>
            </div>
            <h3 class="mt-4">Filtrowanie</h3>
            <div class="box">
                <h5>Liczba osób</h5>
                <label>
                    <input type="number" min="1" max="10" name="people">
                </label>
            </div>
            <div class="box">
                <h5>Cena</h5>
                <div class="price">
                    <input type="number" min="{{ $min_price }}" max="{{ $max_price }}" placeholder="od"
                           value="{{$min_price}}" name="min-price">
                    <span> - </span>
                    <input type="number" min="{{ $min_price }}" max="{{ $max_price }}" placeholder="do"
                           value="{{$max_price}}" name="max-price">
                </div>
            </div>
            <div class="box">
                <h5>Odległość</h5>
                <label>
                    <input type="radio" name="distance" value="20">
                    <span>poniżej 20m</span>
                </label>
                <label>
                    <input type="radio" name="distance" value="50">
                    <span>poniżej 50m</span>
                </label>
                <label>
                    <input type="radio" name="distance" value="100">
                    <span>poniżej 100m</span>
                </label>
                <label>
                    <input type="radio" name="distance" value="200">
                    <span>poniżej 200m</span>
                </label>
                <label>
                    <input type="radio" name="distance" value="100000000" checked>
                    <span>bez znaczenia</span>
                </label>
            </div>
            <div class="box">
                <h5>Udogodnienia</h5>
                <div>
                    @php($i = 0)
                    @foreach($tags as $tag)
                        @php($i++)
                        @if($i == 20)
                </div>
                <h3 onclick="show()" id="button-to-hide">
                    Pokaż pozostałe ({{ count($tags) - $i + 1 }})
                </h3>
                <div id="rest-to-show" class="d-none">
                    @endif
                    <label class="{{$tag->count == 0 ? "inactive" : ""}}">
                        <input type="checkbox" name="tag[]" value="{{ $tag->id }}">
                        <span>{{ $tag->name }} ({{ $tag->count }})</span>
                    </label>
                    @endforeach
                </div>
            </div>
            <button type="submit">Szukaj</button>
        </form>
        <div class="col-8">
            <div class="search-result-header-box">
                <h3>Wyniki wyszukiwania</h3>
                <select name="sort">
                    <option value="price">Cena - malejąco</option>
                    <option value="price">Cena - rosnąco</option>
                    <option value="distance">Odległość - malejąco</option>
                    <option value="distance">Odległość - rosnąco</option>
                </select>
            </div>
            <div class="search-result-box">
                <div class="rooms">
                    @foreach($rooms as $room)
                        <x-room-card :room="$room"/>
                    @endforeach
                </div>
            </div>
        </div>
@endsection
