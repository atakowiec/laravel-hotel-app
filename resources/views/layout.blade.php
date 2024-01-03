<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>@yield("title") - Hotel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    @livewireStyles

    @stack('css')
    @stack('other-scripts')

    @vite(['resources/sass/app.scss'])
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">
            <img src=" {{ asset('images/logo.svg') }}" alt="logo">
            <span class="fw-bold">Hotel</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">
                        Wyszukiwarka
                    </a>
                </li>
            </ul>
            todo: user menu
        </div>
    </div>
</nav>
<div class="app">
    @yield('content')
</div>
@livewireScripts
</body>
