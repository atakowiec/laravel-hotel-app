<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use App\Models\Room;
use App\Traits\WithFloatingComponent;
use Illuminate\View\View;
use Livewire\Component;

class AdminPanel extends Component
{
    public function render(): View
    {
        return view('livewire.admin-panel');
    }
}
