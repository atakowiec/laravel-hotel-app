@php
    $max_price = ceil(\App\Models\Room::max('price'));
    $min_price = floor(\App\Models\Room::min('price'));
@endphp
@section('title', "Strona głowna")

@push("css")
    @vite(["/resources/sass/main.scss"])
@endpush

<div class="main row mx-auto col-12 col-md-9">
    <div class="filters col-3">
        <h3>Termin</h3>
        <div class="box">
            <label>
                <span>Od</span>
                <input type="date"
                       name="date-from"
                       wire:model="dateFrom">
            </label>
            <label>
                <span>Do</span>
                <input type="date"
                       name="date-to"
                       wire:model="dateTo">
            </label>
            @error('dateFrom')
            <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <h3 class="mt-4">Filtrowanie</h3>
        <div class="box">
            <h5>Liczba osób</h5>
            <label>
                <input type="number" min="1" max="10" name="people" wire:model.debounce.200ms="people">
            </label>
            @error('people')
            <span class="error">{{ $message }}</span>
            @enderror
        </div>
        <div class="box">
            <h5>Cena</h5>
            <div class="price">
                <label>
                    <input type="number"
                           min="{{ $min_price }}"
                           max="{{ $max_price }}"
                           placeholder="od"
                           wire:model.debounce.200ms="minPrice"
                           name="min-price">
                </label>
                <span> - </span>
                <label>
                    <input type="number"
                           min="{{ $min_price }}"
                           max="{{ $max_price }}"
                           placeholder="do"
                           wire:model.debounce.200ms="maxPrice"
                           name="max-price">
                </label>
            </div>
                @error('minPrice')
                <div class="error">{{ $message }}</div>
                @enderror
                @error('maxPrice')
                <div class="error">{{ $message }}</div>
                @enderror
        </div>
        <div class="box">
            <h5>Odległość</h5>
            <label>
                <input type="radio" name="distance"
                       value="20" wire:model="distance">
                <span>poniżej 20m</span>
            </label>
            <label>
                <input type="radio" name="distance"
                       value="50" wire:model="distance">
                <span>poniżej 50m</span>
            </label>
            <label>
                <input type="radio" name="distance"
                       value="100" wire:model="distance">
                <span>poniżej 100m</span>
            </label>
            <label>
                <input type="radio" name="distance"
                       value="200" wire:model="distance">
                <span>poniżej 200m</span>
            </label>
            <label>
                <input type="radio" name="distance"
                       value="-1" wire:model="distance">
                <span>bez znaczenia</span>
            </label>
        </div>
        <div class="box">
            <h5>Udogodnienia</h5>
            <div>
                @php($i = 0)
                @foreach($this->tags as $tag)
                    @php($i++)
                    @if($i == 20)
            </div>
            <p onclick="show()" id="button-to-hide">
                Pokaż pozostałe ({{ count($this->tags) - $i + 1 }})
            </p>
            <div id="rest-to-show" class="d-none">
                @endif
                <label class="{{$tag->count == 0 ? "inactive" : ""}}">
                    <input type="checkbox" name="tag[]"
                           value="{{ $tag->id }}"
                           {{ in_array($tag->id, $this->tag) ? "checked" : ""}}
                           wire:change="setTag({{ $tag->id }}, $event.target.checked)">
                    <span>{{ $tag->name }} ({{ $tag->count }})</span>
                </label>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-9 col-xxl-8">
        <div class="search-result-header-box">
            <h3>Wyniki wyszukiwania</h3>
            <div>
                <label>
                    <select name="sort" wire:model="sort">
                        <option value="price:desc">
                            Cena - malejąco
                        </option>
                        <option value="price:asc">
                            Cena - rosnąco
                        </option>
                        <option value="distance:desc">
                            Odległość - malejąco
                        </option>
                        <option value="distance:asc">
                            Odległość - rosnąco
                        </option>
                        <option value="area:desc">
                            Wielkość - malejąco
                        </option>
                        <option value="area:asc">
                            Wielkość - rosnąco
                        </option>
                        <option value="reservations:desc">
                            Popularność - malejąco
                        </option>
                        <option value="reservations:asc">
                            Popularność - rosnąco
                        </option>
                    </select>
                </label>
            </div>
        </div>
        <div class="search-result-box">
            <div wire:loading class="w-100 mt-5">
                <x-loading-animation size="lg"/>
            </div>
            <div wire:loading.remove>
                <div class="rooms">
                    @foreach($this->getPageRooms() as $room)
                        <x-room-card :room="$room"/>
                    @endforeach

                    @if(count($this->getPageRooms()) == 0)
                        <div class="no-data-box">
                            <h3>Brak wyników wyszukiwania</h3>
                            <p>Spróbuj zmienić kryteria wyszukiwania</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="pagination-box">
            <form class="buttons-box" wire:submit.prevent="$set('page', page)">
                <button onclick="changePage(-1)" type="button" id="btn1">
                    &lt;
                </button>
                <label for="page-value"></label>
                <input class="page-value" id="page-value" name="page-value" value="{{$this->page}}">
                <button onclick="changePage(1)" type="button" id="btn2">
                    &gt;
                </button>
                <button class="search" id="btn3">
                    Szukaj
                </button>
            </form>
            <div class="page-info">
                Pokazywanie
                <span>
                {{ max(0, $this->page-1) * \App\Http\Livewire\RoomList::$PER_PAGE + 1 }}
                </span> do
                <span>
                {{ min($rooms->count(), max(0, $this->page) * \App\Http\Livewire\RoomList::$PER_PAGE) }}
                </span>
                z
                <span>
                {{ max(0, $rooms->count()) }}
                </span> wyników
            </div>
        </div>

    </div>

    @push('other-scripts')
        <script>
            let page = {{$this->page}};
            const pageValueInput = document.getElementById("page-value");
            const toShow = document.getElementById("rest-to-show");
            const button = document.getElementById("button-to-hide");

            function show() {
                button.classList.add("d-none");
                toShow.classList.remove("d-none");
            }

            function changePage(offset) {
                page = Math.min({{$max_pages}}, Math.max(1, page + offset));
                pageValueInput.value = page;
            }
        </script>
    @endpush
</div>
