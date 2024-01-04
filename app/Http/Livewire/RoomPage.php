<?php

namespace App\Http\Livewire;

use App\Models\Room;
use Illuminate\View\View;
use Livewire\Component;

class RoomPage extends Component
{
    public Room $room;
    public array $tags = [];

    public string $dateFrom = '';
    public string $dateTo = '';

    public function mount() : void
    {
        $this->tags = Room::getTags($this->room->id);

        // reading data from session
        $this->dateFrom = request()->session()->get('dateFrom', date('Y-m-d'));
        $this->dateTo = request()->session()->get('dateTo', date('Y-m-d', strtotime('+1 day')));
    }

    public function render() : View
    {
        return view('livewire.room-page');
    }
}
