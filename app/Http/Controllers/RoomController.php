<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{

    public function __construct()
    {
    }

    public static array $filters = ['tag', 'date-from', 'date-to', 'people', 'min-price', 'max-price', 'distance', 'sort'];

    public function index()
    {
        return view('main');
    }

    public function show($room_id)
    {
        return view('room', ['room' => Room::findOrFail($room_id)]);
    }
}
