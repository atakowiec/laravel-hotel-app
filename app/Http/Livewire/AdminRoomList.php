<?php

namespace App\Http\Livewire;

use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomRating;
use App\Models\RoomTags;
use App\Traits\WithFlashMessage;
use App\Traits\WithFloatingConfirmation;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class AdminRoomList extends Component
{
    use WithFloatingConfirmation;
    use WithFlashMessage;

    protected $queryString = [
        'name' => ['except' => ''],
        'capacity' => ['except' => '-1'],
    ];

    protected $rules = [
        'name' => ['string', 'nullable'],
        'capacity' => ['string', 'nullable'],
    ];

    public array $capacities;

    public string $name = "";
    public string $capacity = "-1";
    public Collection $rooms;

    public function __construct()
    {
        parent::__construct();

        $this->updated();

        $this->capacities = [];
        $t = Room::select('capacity')->distinct()->orderBy("capacity")->get();
        foreach ($t as $capacity) {
            $this->capacities[] = $capacity->capacity;
        }
    }

    public function updated(): void
    {
        $this->rooms = Room::where('name', 'like', "%$this->name%")
            ->when($this->capacity != "-1", function ($query) {
                return $query->where('capacity', $this->capacity);
            })
            ->get();
    }

    public function deleteRoom($id): void
    {
        if(!auth()?->user()?->admin) {
            $this->addFlashMessage('Nie masz uprawnień do wykonania tej akcji');
            return;
        }

        // first delete each room tag
        $roomTags = RoomTags::where('room_id', $id)->delete();

        // then delete rating
        $roomRating = RoomRating::where('room_id', $id)->delete();

        // then delete reservations
        $reservations = Reservation::where('room_id', $id)->delete();

        Room::destroy($id);

        $this->updated();
        $this->addFlashMessage("Pomyślnie usunięto pokój");
    }

    public function render(): View
    {
        return view('livewire.admin-room-list');
    }

    public function onConfirm($id, ...$params): void
    {
        if ($id == "deleteRoom") {
            $this->deleteRoom($params[0]);
        }
    }
}
