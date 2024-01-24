@extends('layouts.app')

@section('page')
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src=" {{ asset('images/logo.svg') }}" alt="logo">
                <span class="fw-bold">Hotel</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler"
                    aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarToggler">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/">
                            Wyszukiwarka
                        </a>
                    </li>
                    @can('admin', Auth::user())
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/admin">
                                Zarządzanie pokojami
                            </a>
                        </li>
                    @endcan
                </ul>
                @auth
                    <div class="nav-item pe-0 pe-md-2">
                        <a class="nav-link" href="{{ route('logout') }}">
                            Wyloguj
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link gray-nav-button profile-link" href="{{ route('profile') }}">
                            <img src="{{asset("/images/user.png")}}" class="profile-avatar" alt="profile avatar">
                            {{auth()->user()->nickname}}
                        </a>
                    </div>
                @else
                    <div class="nav-item">
                        <a class="nav-link gray-nav-button" href="{{ route('register') }}">
                            Zarejestruj się
                        </a>
                    </div>
                    <div class="nav-item">
                        <a class="nav-link login-link" href="{{ route('login') }}">
                            Zaloguj się
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>
    <div class="app">
        @yield('content')
    </div>
@endsection
