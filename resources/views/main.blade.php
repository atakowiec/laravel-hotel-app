@php
    use App\Models\Room;
    use App\Models\RoomTags;
    use App\Models\AvailableTags;

    $max_price = ceil(Room::max('price'));
    $min_price = floor(Room::min('price'));

    $args = [
        'date-from' => ['regex' => '/^\d{4}-\d{2}-\d{2}$/'],
        'date-to' => ['regex' => '/^\d{4}-\d{2}-\d{2}$/'],
        'people' => FILTER_SANITIZE_NUMBER_INT,
        'min-price' => FILTER_SANITIZE_NUMBER_INT,
        'max-price' => FILTER_SANITIZE_NUMBER_INT,
        'distance' => FILTER_SANITIZE_NUMBER_INT,
        'tag' => ['filter' => FILTER_SANITIZE_NUMBER_INT, 'flags' => FILTER_REQUIRE_ARRAY],
        'sort' => ['regex' => '/^(price|distance|area):(asc|desc)$/']
    ];

    // filter input data, just skip invalid values
    $data = filter_input_array(INPUT_GET, $args);

    // set default values
    $data['date-from'] = $data['date-from'] ?? date('Y-m-d');
    $data['date-to'] = $data['date-to'] ?? date('Y-m-d', strtotime('+1 day'));
    $data['people'] = $data['people'] ?? 1;
    $data['min-price'] = $data['min-price'] ?? $min_price;
    $data['max-price'] = $data['max-price'] ?? $max_price;
    $data['distance'] = $data['distance'] ?? "-1";
    $data['tag'] = $data['tag'] ?? [];
    $data['sort'] = $data['sort'] ?? "price:desc";
@endphp
@section('title', "Strona głowna")

@vite(["/resources/sass/main.scss"])

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
        <form class="row col-12">
            <x-filters-box :data="$data" :rooms="$rooms"/>
            <div class="col-8">
                <div class="search-result-header-box">
                    <h3>Wyniki wyszukiwania</h3>
                    <div>
                        <label>
                            <select name="sort">
                                <option value="price:desc" {{ $data["sort"] === "price:desc" ? "selected" : "" }}>
                                    Cena - malejąco
                                </option>
                                <option value="price:asc" {{ $data["sort"] === "price:asc" ? "selected" : "" }}>
                                    Cena - rosnąco
                                </option>
                                <option value="distance:desc" {{ $data["sort"] === "distance:desc" ? "selected" : "" }}>
                                    Odległość - malejąco
                                </option>
                                <option value="distance:asc" {{ $data["sort"] === "distance:asc" ? "selected" : "" }}>
                                    Odległość - rosnąco
                                </option>
                                <option value="area:desc" {{ $data["sort"] === "area:desc" ? "selected" : "" }}>
                                    Wielkość - malejąco
                                </option>
                                <option value="area:asc" {{ $data["sort"] === "area:asc" ? "selected" : "" }}>
                                    Wielkość - rosnąco
                                </option>
                            </select>
                        </label>
                        <button class="submit" type="submit">Sortuj</button>
                    </div>
                </div>
                <div class="search-result-box">
                    <div class="rooms">
                        @foreach($rooms as $room)
                            <x-room-card :room="$room"/>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
