<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{

    public function __construct()
    {
        // empty
    }

    public function index()
    {
        return $this->livewire_nav_view("room-list");
    }

    public function show($room_id)
    {
        return $this->livewire_nav_view('room-page', ['room' => Room::findOrFail($room_id)]);
    }
}
