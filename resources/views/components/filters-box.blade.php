@php
    use App\Models\Room;
    use App\Models\RoomTags;
    use App\Models\AvailableTags;

    $max_price = ceil(Room::max('price'));
    $min_price = floor(Room::min('price'));

    $tags = $getTags();
    $data = $getData();
@endphp

<div class="filters col-3">
    <h3>Termin</h3>
    <div class="box">
        <label>
            <span>Od</span>
            <input type="date" name="date-from" value="{{ $data["date-from"] }}">
        </label>
        <label>
            <span>Do</span>
            <input type="date" name="date-to" value="{{ $data["date-to"] }}">
        </label>
    </div>
    <h3 class="mt-4">Filtrowanie</h3>
    <div class="box">
        <h5>Liczba osób</h5>
        <label>
            <input type="number" min="1" max="10" name="people" value="{{ $data['people'] ?? 1 }}">
        </label>
    </div>
    <div class="box">
        <h5>Cena</h5>
        <div class="price">
            <label>
                <input type="number" min="{{ $min_price }}" max="{{ $max_price }}" placeholder="od"
                       value="{{ $data['min-price'] }}" name="min-price">
                <span> - </span>
                <input type="number" min="{{ $min_price }}" max="{{ $max_price }}" placeholder="do"
                       value="{{ $data['max-price'] }}" name="max-price">
            </label>
        </div>
    </div>
    <div class="box">
        <h5>Odległość</h5>
        <label>
            <input type="radio" name="distance"
                   value="20" {{ $data["distance"] === "20" ? "checked" : "" }}>
            <span>poniżej 20m</span>
        </label>
        <label>
            <input type="radio" name="distance"
                   value="50" {{ $data["distance"] === "50" ? "checked" : "" }}>
            <span>poniżej 50m</span>
        </label>
        <label>
            <input type="radio" name="distance"
                   value="100" {{ $data["distance"] === "100" ? "checked" : "" }}>
            <span>poniżej 100m</span>
        </label>
        <label>
            <input type="radio" name="distance"
                   value="200" {{ $data["distance"] === "200" ? "checked" : "" }}>
            <span>poniżej 200m</span>
        </label>
        <label>
            <input type="radio" name="distance"
                   value="-1" {{ $data["distance"] === "-1" ? "checked" : "" }}>
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
        <p onclick="show()" id="button-to-hide">
            Pokaż pozostałe ({{ count($tags) - $i + 1 }})
        </p>
        <div id="rest-to-show" class="d-none">
            @endif
            <label class="{{$tag->count == 0 ? "inactive" : ""}}">
                <input type="checkbox" name="tag[]"
                       value="{{ $tag->id }}" {{ in_array($tag->id, $data["tag"]) ? "checked" : "" }}>
                <span>{{ $tag->name }} ({{ $tag->count }})</span>
            </label>
            @endforeach
        </div>
    </div>
    <div class="d-flex justify-content-center">
        <button class="submit" type="submit">Filtruj</button>
    </div>
</div>
