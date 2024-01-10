@section('title', "Logowanie")

@push("css")
    @vite(["/resources/sass/login.scss"])
@endpush

@php($valid = $this->getErrorBag()->isEmpty() && !empty($email) && !empty($password))

<div class="login-box col-12 col-sm-8 col-md-4 col-xxl-3">
    <div class="top-buttons-box">
        <a href="/" class="back-button">
            <img src="{{ asset("images/arrow-left.svg") }}" alt="Strona główna">
            Strona główna
        </a>

        <a href="/register" class="login-button">
            Rejestracja
            <img src="{{ asset("images/arrow-right.svg") }}" alt="Rejestracja">
        </a>
    </div>
    <h1>Logowanie</h1>
    <form wire:submit.prevent="login">
        <div class="input-box">
            <label for="email">Email</label>
            <input type="email" id="email" wire:model="email" {{$this->getErrorClass("email")}}>
            @error('email')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        <div class="input-box">
            <label for="password">Hasło</label>
            <input type="password" id="password" wire:model="password" {{$this->getErrorClass("password")}}>
            @error('password')
            <div class="error">
                {{ $message }}
            </div>
            @enderror
        </div>
        @error('login')
        <div class="error">
            {{$message}}
        </div>
        @enderror
        <button @if(!$valid) class="disabled" @endif>
            Zaloguj się
        </button>
        <div class="bottom-buttons-box">
            <a href="/register">
                Nie masz konta? Zarejestruj się!
            </a>
        </div>
    </form>
</div>
