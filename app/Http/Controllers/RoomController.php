<?php

namespace App\Http\Controllers;

use App\Models\Room;

class RoomController extends Controller
{

    public function __construct(
        private Room $model
    )
    {
    }

    private static array $filters = ['tag', 'date-from', 'date-to', 'people', 'min-price', 'max-price', 'distance', 'sort'];

    public function index()
    {
        return view('main', ["rooms" => $this->model->filter(request(RoomController::$filters))->get()]);
    }

    public function show($room_id)
    {
        return view('room', ['room' => Room::findOrFail($room_id)]);
    }
}
