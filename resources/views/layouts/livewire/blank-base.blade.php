@props(['liveWireComponent'])

@extends("layouts.app")

@section('page')
    @livewire($liveWireComponent, $params)
@endsection
