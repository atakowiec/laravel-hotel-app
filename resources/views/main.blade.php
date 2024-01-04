@section('title', "Strona głowna")

@push("css")
    @vite(["/resources/sass/main.scss"])
@endpush

@extends('layout')

@section('content')
    <div class="main row mx-auto col-12 col-md-9">
        @livewire('room-list')
    </div>
@endsection
