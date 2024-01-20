<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use App\Models\Room;
use App\Traits\WithFloatingComponent;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class RoomPage extends Component
{
    use WithFloatingComponent;

    public Room $room;
    public array $tags = [];
    public string $dateFrom = '';
    public string $dateTo = '';
    public bool $available = false;
    public float $totalPrice = 0;
    public int $days = 0;

    public int $shownComments = 10;

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

    public function bookRoom(): void
    {
        $this->validate();

        $reservation = Reservation::create([
            'user_id' => auth()->user()->id,
            'room_id' => $this->room->id,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'total_cost' => $this->totalPrice,
            'cancelled' => false
        ]);

        redirect("/profile", ["message" => "Pokój zostal zarezerwowany"]);
    }

    public function showMoreComments() : void
    {
        $this->shownComments += 10;
    }

    public function updateAvailable(): void
    {
        $dateFrom = $this->dateFrom;
        $dateTo = $this->dateTo;

        $reservations = Reservation::where('room_id', $this->room->id)
            ->where(function ($query) use ($dateFrom, $dateTo) {
                $query->orWhere(function ($query) use ($dateFrom, $dateTo) {
                        $query->where('date_from', '<', $dateFrom)
                            ->where('date_to', '>', $dateTo);
                    })
                    ->orWhere(function ($query) use ($dateFrom, $dateTo) {
                        $query->where('date_to', '>', $dateFrom)
                            ->where('date_to', '<', $dateTo);
                    })
                    ->orWhere(function ($query) use ($dateFrom, $dateTo) {
                        $query->where('date_from', '>', $dateFrom)
                            ->where('date_from', '<', $dateTo);
                    });
            })
            ->get();

        $this->available = count($reservations) == 0;
    }

    public function updateTotalPrice(): void
    {
        // days between dateFrom and dateTo (including) times price
        $this->days = (strtotime($this->dateTo) - strtotime($this->dateFrom)) / (60 * 60 * 24);
        $this->totalPrice = $this->days * $this->room->price;
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
