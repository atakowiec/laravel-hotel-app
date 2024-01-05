@section('title', "Rejestracja")

@push("css")
    @vite(["/resources/sass/login.scss"])
@endpush

@php($valid = $this->getErrorBag()->isEmpty() && !empty($email) && !empty($password) && !empty($nickname) && !empty($password_confirmation) && $terms == true)

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
    <form wire:submit.prevent="register">
        <div class="input-box">
            <label for="nickname">Nazwa użytkownika</label>
            <input type="text" id="nickname" wire:model.debounce.200ms="nickname" {{$this->getErrorClass("nickname")}}>
            @error('nickname')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="input-box">
            <label for="email">Email</label>
            <input type="email" id="email" wire:model.debounce.200ms="email" {{$this->getErrorClass("email")}}>
            @error('email')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="input-box">
            <label for="password">Hasło</label>
            <input type="password" id="password"
                   wire:model.debounce.200ms="password" {{$this->getErrorClass("password")}}>
            @error('password')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="input-box">
            <label for="password_confirmation">Powtórz hasło</label>
            <input type="password" id="password_confirmation"
                   wire:model.debounce.200ms="password_confirmation" {{$this->getErrorClass("password_confirmation")}}
            >
            @error('password_confirmation')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
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
            Zarejestruj się
        </button>
        <div class="bottom-buttons-box">
            <a href="/login">
                Masz już konto? Zaloguj się!
            </a>
        </div>
    </form>
</div>
