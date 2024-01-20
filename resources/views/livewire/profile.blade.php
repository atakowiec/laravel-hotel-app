@section('title', 'Profil')

@push("css")
    @vite('resources/sass/profile.scss')
@endpush

@php
    use App\Models\Reservation;
    $reservationId = $this->getParam('review', 0);
    if($reservationId != null && $reservationId > 0) {
        $reservation = Reservation::find($reservationId);
    }
@endphp

@php($user = auth()->user())
@php($address = $user->address)


<div class="row mx-auto col-12 col-md-9 col-xxl-7 profile-box">
    <div class="left-panel col-3">
        <div class="text-center">
            <div class="avatar-box">
                <img src="{{ asset("images/user.png") }}" alt="avatar" class="avatar">
            </div>
            <div class="info-box">
                <div class="name">
                    {{$user->nickname}}
                </div>
                <h4>
                    Dane kontaktowe
                </h4>
                <div class="email">{{$user->email}}</div>
                <div class="phone-number">{{number_format($user->phone_number, thousands_separator: " ")}}</div>
            </div>
            <h4 class="mt-3">
                Adres
            </h4>
            <div class="address">
                {{$address->city}} {{ $address->zip_code }},
                <br> ul. {{ $address->street }} {{ $address->building_number }}
                @if(!empty($address->flat_number))
                    / {{$address->flat_number}}
                @endif
            </div>
        </div>
        <div>
            <button wire:click="showFloatingComponent('change_password')">
                Zmień hasło
            </button>
            <button wire:click="showFloatingComponent('remove_account')">
                Usun konto
            </button>
        </div>
    </div>
    <div class="reservations-box col-9">
        @if(!$hasAnyReservations)
            <h2 class="no-reservations">
                Nie masz żadnych rezerwacji
            </h2>
        @endif
        @if($currentReservations->count() > 0)
            <h2>
                Aktualne rezerwacje
            </h2>
            @foreach($currentReservations as $reservation)
                <x-reservation-card :reservation="$reservation"/>
            @endforeach
        @elseif($nextReservation != null)
            <h2>
                Następna rezerwacja
            </h2>
            <x-reservation-card :reservation="$nextReservation"/>
        @endif
        @if($this->futureReservations->count() > 0)
            <h2>
                Nadchodzące rezerwacje
            </h2>
            @foreach($futureReservations as $reservation)
                <x-reservation-card :reservation="$reservation"/>
            @endforeach
        @endif
        @if($pastReservations->count() > 0)
            <h2>
                Poprzednie rezerwacji
            </h2>
            @foreach($pastReservations as $reservation)
                <x-reservation-card :reservation="$reservation"/>
            @endforeach
        @endif
    </div>

    <x-floating-confirmation
        id="cancel_reservation"
        title="Anulowanie rezerwacji"
        message="Czy na pewno chcesz anulować rezerwację?"
        acceptText="Anuluj rezerwację"
        cancelText="Nie, nie anuluj"
    />

    <x-floating-confirmation
        id="remove_account"
        title="Usuniecie konta"
        message="Czy na pewno chcesz usunąć konto? Wszystkie dane zostaną bezpowrotnie usunięte!"
        acceptText="Usuń konto"
        cancelText="Nie, nie usuwaj"
    />

    <x-floating-container id="review" class="write-review-box">
        <h1>Dodaj opinie</h1>
        <h4>{{ $reservation->room->name }}</h4>
        <div class="stars">
            @for($i = 1; $i<=5; $i+=1)
                <div class="star" wire:click="$set('rating', {{$i}})">
                    <img src="{{ $i <= $rating ? asset("images/full-star.png") : asset("images/empty-star.png") }}"
                         alt="star">
                </div>
            @endfor
        </div>
        @error('rating')
            <span class="error">{{ $message }}</span>
        @enderror
        <div class="input-box">
            <label for="review-content">Opinia</label>
            <textarea id="review-content" wire:model.debounce.300ms="comment"></textarea>
            @error('content') <span class="error">{{ $message }}</span> @enderror
        </div>
        @error('comment')
            <span class="error">{{ $message }}</span>
        @enderror
        <div class="button-box">
            <button wire:click="addReview">
                Dodaj opinię
            </button>
        </div>
    </x-floating-container>

    <x-floating-container id="change_password">
        <h1>
            Zmiana hasła
        </h1>
        <div class='change-password-box col-11 col-md-8 col-xxl-6 mx-auto'>
            <div class="input-box">
                <label for="old-password">Stare hasło</label>
                <input type="password" id="old-password" wire:model.debounce.300ms="oldPassword">
                @error('oldPassword') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="input-box">
                <label for="new-password">Nowe hasło</label>
                <input type="password" id="new-password" wire:model.debounce.300ms="newPassword">
                @error('newPassword') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="input-box">
                <label for="new-password-confirmation">Potwierdź nowe hasło</label>
                <input type="password" id="new-password-confirmation"
                       wire:model.debounce.300ms="newPasswordConfirmation">
                @error('newPasswordConfirmation') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="button-box">
                <button wire:click="changePassword">
                    Zmień hasło
                </button>
            </div>
        </div>
    </x-floating-container>
</div>

@push('other-scripts')
@endpush
