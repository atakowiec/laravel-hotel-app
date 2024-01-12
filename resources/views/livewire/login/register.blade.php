@section('title', "Rejestracja")

@push("css")
    @vite(["/resources/sass/login.scss"])
@endpush

@php($valid = (!$nextStage && $this->getErrorBag()->isEmpty() && !empty($email) && !empty($password) && !empty($nickname) && !empty($password_confirmation) && $terms == true)
            || ($nextStage && $this->getErrorBag()->isEmpty() && !empty($city) && !empty($zip_code) && !empty($street) && !empty($building_number) && !empty($flat_number)))

<div class="login-box col-12 col-sm-8 col-md-4 col-xxl-3">
    <div class="top-buttons-box">
        <a href="/" class="back-button">
            <img src="{{ asset("images/arrow-left.svg") }}" alt="Strona główna">
            Strona główna
        </a>

        <a href="/login" class="login-button">
            Logowanie
            <img src="{{ asset("images/arrow-right.svg") }}" alt="Logowanie">
        </a>
    </div>
    <h1>Rejestracja</h1>
    @if(!$nextStage)
        <form wire:submit.prevent="runNextStage">
            <x-input-box id="nickname" label="Nazwa użytkownia" type="nickname"/>
            <x-input-box id="email" label="Email" type="email"/>
            <x-input-box id="password" label="Hasło" type="password"/>
            <x-input-box id="password_confirmation" label="Powtórz hasło" type="password"/>
            <x-input-box id="phone_number" label="Numer telefonu"/>
            <div class="input-box">
                <label>
                    <input type="checkbox" id="terms" wire:model="terms">
                    Akceptuję regulamin
                </label>
                @error('terms')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
            </div>

            <button @if(!$valid) class="disabled" @endif>
                Dalej
            </button>
            <div class="bottom-buttons-box">
                <a href="/login">
                    Masz już konto? Zaloguj się!
                </a>
            </div>
        </form>
    @else
        <form class="row" wire:submit.prevent="register">
            <x-input-box id="city" label="Miasto"/>
            <x-input-box id="zip_code" label="Kod pocztowy"/>
            <x-input-box id="street" label="Ulica"/>
            <x-input-box id="building_number" label="Numer budynku" class="col-6"/>
            <x-input-box id="flat_number" label="Numer lokalu" class="col-6"/>

            <div class="col-12">
                <button  @if(!$valid) class="disabled" @endif>
                    Zarejestruj się
                </button>
                <button type="button" class="back-button" wire:click="$set('nextStage', false)">
                    Powrót
                </button>
            </div>
            <div class="bottom-buttons-box">
                <a href="/login">
                    Masz już konto? Zaloguj się!
                </a>
            </div>
        </form>
    @endif
</div>
