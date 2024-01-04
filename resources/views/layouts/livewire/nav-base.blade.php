@props(['liveWireComponent'])

@extends("layouts.navbar")

@section('content')
    @livewire($liveWireComponent, $params)
@endsection
