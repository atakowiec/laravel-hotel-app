@section('title', 'Edytuj pokój')

@push("css")
    @vite("resources/sass/admin.scss")
@endpush


<div class="edit-room col-12 col-md-7 col-xxl-4 mx-auto px-3 px-md-0">
    <h1 class="text-center">
        Edytuj pokój
    </h1>
    <form class="row" wire:submit.prevent="save">
        <div class="photo-box">
            <label for="roomPhoto" class="col-12 mx-auto d-block">
                @if($this->isCorrectPhoto())
                    <img src="{{ $roomPhoto->temporaryUrl() }}" alt="Zdjęcie pokoju">
                @else
                    <img src="{{ empty($initialPhoto) ? asset("images/room.jpg") : asset("storage/rooms/$initialPhoto") }}" alt="Zdjęcie pokoju">
                @endif
            </label>
            <input type="file" id="roomPhoto"
                   wire:model="roomPhoto" {{$this->getErrorClass('roomPhoto')}}>
            @error('roomPhoto')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        <x-input-box label="Nazwa pokoju" id="roomName"/>
        <x-input-box label="Liczba osob" id="roomCapacity" class="col-6" type="number"/>
        <x-input-box label="Powierzchnia" id="roomArea" class="col-6" type="number"/>
        <x-input-box label="Cena za noc" id="roomPrice" type="number"/>
        <x-input-box label="Pozycja X" id="roomXPos" class="col-6" type="number"/>
        <x-input-box label="Pozycja Z" id="roomZPos" class="col-6" type="number"/>
        <div class="tags-box">
            <h3>
                Wybierz tagi
            </h3>
            @foreach($allTags as $tag)
                <input type="checkbox"
                       @if(in_array($tag->id, $roomTags)) checked @endif
                       wire:click="setTag({{$tag->id}}, $event.target.checked)"
                       class="tag-checkbox"
                       value="{{$tag->id}}"
                       id='{{ $tag->id }}'/>
                <label for='{{ $tag->id }}'>{{$tag->name}}</label>
            @endforeach
        </div>

        <div class="col-12 text-center">
            <button>
                @if($roomId == "-1")
                    Dodaj
                @else
                    Zapisz
                @endif
            </button>
        </div>
    </form>
</div>
