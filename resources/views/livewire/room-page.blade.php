@section("title", $room->name)

@push("css")
    @vite("resources/sass/room.scss")
@endpush

@php
    $rating = [];
    $userComment = null;

    $isAnyComment = false;
    foreach ($room->rating as $rate) {
        if ($rate->user_id == auth()->id()) {
            $userComment = $rate;
        } else {
            $rating[] = $rate;
        }

        if ($rate->comment != null) {
            $isAnyComment = true;
        }
    }

    $reviewToRemove = \App\Models\RoomRating::find($this->getParam("remove_review"));
    $reviewNickname = $reviewToRemove != null ? $reviewToRemove->user->nickname : "";

@endphp

@php($valid = $this->getErrorBag()->isEmpty())

<div class="row col-12 col-md-10 col-xxl-8 mx-auto room-box">
    <div class="col-12 info-box">
        <div class="info">
            <h3>
                {{ $room->name }}
                <span class="id">#{{ $room->id }}</span>
            </h3>
            <x-rating-stars :room_id="$room->id"/>
            <div class="details">
                {{$room->capacity}}-osobowy, {{$room->area}}m<sup>2</sup>,
                x={{$room->x_pos}}, y={{$room->y_pos}}, z={{$room->z_pos}} ({{round($room->distance)}}m. od wejscia)
            </div>
        </div>
        <div class="row">
            <div class="col-9 me-0">
                <img class="room-image" src="{{ asset("storage/rooms/$room->photo") }}" alt="room"/>
            </div>
            <div class="col-3 book-box">
                <div class="box">
                    <h4>Zarezerwuj</h4>
                    <label>
                        <span>Od</span>
                        <input type="date" name="date-from" wire:model="dateFrom">
                    </label>
                    @error("dateFrom")
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <label>
                        <span>Do</span>
                        <input type="date" name="date-to" wire:model="dateTo">
                    </label>
                    @error("dateTo")
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <div class="price">
                        <span class="value">{{ $room->price }} zł / noc</span>
                    </div>
                </div>
                <div class="book-button">
                    @if(auth()->check())
                        <button wire:click="teleport" class="mb-2" style="background-color: #cccccc; color: #212121">
                            Teleport
                        </button>
                    @endif
                    <button @if(!$valid || !$available || !auth()->check()) disabled
                            @else wire:click="showFloatingComponent('book-room')"
                            @endif class="submit">
                        <span wire:loading.class.remove="d-none" class="d-none d-flex">
                            <x-loading-animation size="sm"/>
                        </span>
                        <span wire:loading.remove>
                        @if($valid)
                                @if(!auth()->check())
                                    Zaloguj się aby zarezerwować
                                @elseif($available)
                                    Zarezerwuj za {{ $totalPrice }} zł
                                @else
                                    Termin zajęty
                                @endif
                            @else
                                Zarezerwuj
                            @endif
                        </span>
                    </button>
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
    @if($isAnyComment)
        <div class="col-12 ratings-box">
            @if($userComment != null)
                <h4>Twoja opinia</h4>
                <div class="rating">
                    <div class="user">
                        <div>
                            <div class="name">{{ $userComment->user->nickname }}</div>
                            <div class="date">{{ $userComment->created_at }}</div>
                        </div>
                        @can("admin", Auth::user())
                            <div>
                                <button wire:click="showFloatingComponent('remove_review', {{ $userComment->id }})">
                                    Usun
                                </button>
                            </div>
                        @endcan
                    </div>
                    <div class="stars">
                        <x-rating-stars :rating="$userComment->value"/>
                    </div>
                    @unless($userComment->comment == null)
                        <div class="comment">
                            {{ $userComment->comment }}
                        </div>
                    @endunless
                </div>
            @endif

            <h4>Opinie innych użytkowników</h4>
            @php($i = 0)
            @foreach($rating as $rate)
                @if($i++ >= $shownComments)
                    @break
                @endif

                @if($rate->comment != null)
                    <div class="rating">
                        <div class="user">
                            <div>
                                <div class="name">{{ $rate->user->nickname }}</div>
                                <div class="date">{{ $rate->created_at }}</div>
                            </div>
                            @can("admin", Auth::user())
                                <div>
                                    <button wire:click="showFloatingComponent('remove_review', {{ $rate->id }})">
                                        Usun
                                    </button>
                                </div>
                            @endcan
                        </div>
                        <div class="stars">
                            <x-rating-stars :rating="$rate->value"/>
                        </div>
                        <div class="comment">
                            {{ $rate->comment }}
                        </div>
                    </div>
                @endif
            @endforeach
            @if($i++ >= $shownComments)
                <div class="text-center show-more">
                    <button wire:click="showMoreComments">
                        Pokaż więcej
                    </button>
                </div>
            @endif
        </div>
    @endif

    <x-floating-confirmation
        id="remove_review"
        title="Usuwanie opinii"
        message="Czy na pewno chcesz usunąć opinie uzytkownika {{$reviewNickname}}?"
        acceptText="Usun rezerwacje"
        cancelText="Nie, nie usuwaj"
    />

    <x-floating-container id="book-room">
        <h1>
            Potwierdzenie rezerwacji
        </h1>
        <div class="confirmation-values-box">
            <div class="key">
                Data zameldowania: <b>{{ $dateFrom }}</b>
            </div>
            <div class="key">
                Data wymeldowania: <b>{{ $dateTo }}</b>
            </div>
            <div class="key">
                Kwota: <b>{{ $days }} dni * {{ $room->price }}zł = {{ $totalPrice }}zł</b>
            </div>
            <div class="text-center mt-3">
                <button wire:click="bookRoom">
                    Zarezerwuj
                </button>
            </div>
        </div>
    </x-floating-container>
</div>
