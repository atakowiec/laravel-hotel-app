@extends('layout')

@section("title", $room->name)

@push("css")
    @vite("resources/sass/room.scss")
@endpush

@section("content")
    @livewire('room-page', ['room' => $room])
@endsection
