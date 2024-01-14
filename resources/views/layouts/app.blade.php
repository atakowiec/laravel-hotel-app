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

    @vite(['resources/sass/app.scss'])

    @stack('css')
</head>
<body>
@yield('page')

@livewire('flash-message')

@stack('other-scripts')
@livewireScripts
</body>
