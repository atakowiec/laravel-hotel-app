<?php

namespace App\Http\Livewire;

use App\Models\AvailableTags;
use App\Models\Room;
use App\Traits\WithFlashMessage;
use App\Traits\WithInputErrorClass;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\File;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditRoom extends Component
{
    use WithInputErrorClass;
    use WithFileUploads;
    use WithFlashMessage;

    protected $queryString = [
        'roomId' => ['except' => "-1"],
    ];

    public Collection $allTags;

    public string $roomId = "-1";
    public $roomPhoto;
    public string $roomName = "";
    public string $roomCapacity = "";
    public string $roomArea = "";
    public string $roomPrice = "";
    public string $roomXPos = "";
    public string $roomZPos = "";
    public array $roomTags = [];
    public string $initialPhoto = "";

    protected $messages = [
        'roomPhoto.required' => 'Zdjęcie jest wymagane.',
        'roomPhoto.image' => 'Zdjęcie musi być obrazem.',
        'roomPhoto.max' => 'Zdjęcie nie może być większe niż 8MB.',
        'roomPhoto.mimes' => 'Zdjęcie musi być w formacie jpg, jpeg lub png.',
        'roomName.required' => 'Nazwa jest wymagana.',
        'roomName.string' => 'Nazwa musi być tekstem.',
        'roomName.max' => 'Nazwa nie może być dłuższa niż 255 znaków.',
        'roomCapacity.required' => 'Liczba osob jest wymagana.',
        'roomCapacity.integer' => 'Liczba osob musi być liczbą całkowitą.',
        'roomCapacity.min' => 'Liczba osob musi być większa od 0.',
        'roomArea.required' => 'Powierzchnia jest wymagana.',
        'roomArea.integer' => 'Powierzchnia musi być liczbą całkowitą.',
        'roomArea.min' => 'Powierzchnia musi być większa od 0.',
        'roomPrice.required' => 'Cena jest wymagana.',
        'roomPrice.numeric' => 'Cena musi być liczbą.',
        'roomPrice.min' => 'Cena musi być większa od 0.',
        'roomXPos.required' => 'Pozycja X jest wymagana.',
        'roomXPos.integer' => 'Pozycja X musi być liczbą całkowitą.',
        'roomZPos.required' => 'Pozycja Z jest wymagana.',
        'roomZPos.integer' => 'Pozycja Z musi być liczbą całkowitą.',
    ];

    protected $rules = [
        'roomPhoto' => ['required', 'image', 'max:8196', 'mimes:jpg,jpeg,png'],
        'roomName' => ['required', 'string', 'max:255'],
        'roomCapacity' => ['required', 'integer', 'min:1'],
        'roomArea' => ['required', 'numeric', 'min:1'],
        'roomPrice' => ['required', 'numeric', 'min:1'],
        'roomXPos' => ['required', 'integer'],
        'roomZPos' => ['required', 'integer']
    ];

    public function isCorrectPhoto(): bool
    {
        if ($this->roomPhoto === null)
            return false;

        $path = strtolower($this->roomPhoto->getRealPath());
        return (str_ends_with($path, '.jpg') || str_ends_with($path, '.jpeg') || str_ends_with($path, '.png'));
    }

    public function updated(string $propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function __construct()
    {
        parent::__construct();
        $this->allTags = new Collection();
    }

    public function mount(): void
    {
        $this->allTags = AvailableTags::all();

        if($this->roomId == "-1") return;

        $room = Room::find($this->roomId);
        $this->roomName = $room->name;
        $this->roomCapacity = $room->capacity;
        $this->roomArea = $room->area;
        $this->roomPrice = $room->price;
        $this->roomXPos = $room->x_pos;
        $this->roomZPos = $room->z_pos;
        $this->roomTags = $room->tags()->pluck('available_tags.id')->toArray();
        $this->initialPhoto = $room->photo;
    }

    public function save(): void
    {
        $this->validate();

        if($this->roomId === "-1")
            $this->createRoom();
        else
            $this->updateRoom();
    }

    private function createRoom(): void
    {
        $room = new Room();
        $room->name = $this->roomName;
        $room->capacity = $this->roomCapacity;
        $room->area = $this->roomArea;
        $room->price = $this->roomPrice;
        $room->x_pos = $this->roomXPos;
        $room->z_pos = $this->roomZPos;
        $room->save();

        $this->savePhoto($room);

        foreach ($this->roomTags as $tagId)
            $room->tags()->attach($tagId);

        $this->roomId = $room->id;

        $this->addFlashMessage("Dodano nowy pokój.");
    }

    private function updateRoom(): void
    {
        $room = Room::find($this->roomId);
        $room->name = $this->roomName;
        $room->capacity = $this->roomCapacity;
        $room->area = $this->roomArea;
        $room->price = $this->roomPrice;
        $room->x_pos = $this->roomXPos;
        $room->z_pos = $this->roomZPos;
        $room->save();

        $this->savePhoto($room);

        $room->tags()->detach();
        foreach ($this->roomTags as $tagId)
            $room->tags()->attach($tagId);

        $this->addFlashMessage("Zaktualizowano pokój.");
    }

    private function savePhoto(Room $room): void
    {
        if ($this->roomPhoto !== null) {
            $path = $this->roomPhoto->store('public/rooms');
            $room->photo = substr($path, strrpos($path, '/') + 1);
            $room->save();
        }
    }

    public function setTag($tagId, $checked): void
    {
        if ($checked)
            $this->roomTags[] = $tagId;
        else
            $this->roomTags = array_diff($this->roomTags, [$tagId]);
    }

    public function render(): View
    {
        return view('livewire.edit-room');
    }
}
