@props(['liveWireComponent'])

@extends("layouts.app")

@section('content')
    @livewire($liveWireComponent, $params)
@endsection
