<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\View\View;
use Livewire\Component;

class RoomPage extends Component
{
    public Room $room;
    public array $tags = [];
    public string $dateFrom = '';
    public string $dateTo = '';
    public bool $available = false;
    public float $totalPrice = 0;

    protected $rules = [
        'dateFrom' => 'required|date|after:yesterday',
        'dateTo' => 'required|date|after:dateFrom',
    ];

    protected $messages = [
        'dateFrom.required' => 'Wybierz datę',
        'dateFrom.date' => 'Niepoprawna data',
        'dateFrom.after' => 'Wybierz datę w przyszłości',
        'dateTo.required' => 'Wybierz datę',
        'dateTo.date' => 'Niepoprawna data',
        'dateTo.after' => 'Niepoprawny przedział dat',
    ];

    public function mount(): void
    {
        $this->tags = Room::getTags($this->room->id);

        // reading data from session
        $this->dateFrom = request()->session()->get('dateFrom', date('Y-m-d'));
        $this->dateTo = request()->session()->get('dateTo', date('Y-m-d', strtotime('+1 day')));

        $this->validate();

        $this->updateAvailable();
        $this->updateTotalPrice();
    }

    public function updateAvailable(): void
    {
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;

        $reservations = Reservation::where('room_id', $this->room->id)
            ->where(function ($query) use ($dateFrom, $dateTo) {
                $query->whereBetween('date_from', [$dateFrom, $dateTo])
                    ->orWhereBetween('date_to', [$dateFrom, $dateTo])
                    ->orWhere(function ($query) use ($dateFrom, $dateTo) {
                        $query->where('date_from', '<=', $dateFrom)
                            ->where('date_to', '>=', $dateTo);
                    });
            })
            ->get();

        $this->available = count($reservations) == 0;
    }

    public function updateTotalPrice(): void
    {
        // days between dateFrom and dateTo (including) times price
        $days = (strtotime($this->dateTo) - strtotime($this->dateFrom)) / (60 * 60 * 24);
        $this->totalPrice = $days * $this->room->price;
    }

    public function updated($field): void
    {
        $this->validate();
        if ($field != 'dateFrom' && $field != 'dateTo') return;

        request()->session()->put('dateFrom', $this->dateFrom);
        request()->session()->put('dateTo', $this->dateTo);

        $this->updateAvailable();
        $this->updateTotalPrice();
    }

    public function render(): View
    {
        return view('livewire.room-page');
    }
}
