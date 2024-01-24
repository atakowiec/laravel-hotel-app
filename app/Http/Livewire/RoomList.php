<?php

namespace App\Http\Livewire;

use App\Models\AvailableTags;
use App\Models\Room;
use App\Models\RoomTags;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class RoomList extends Component
{
    public static int $PER_PAGE = 16;

    public Collection $rooms;

    protected $messages = [
        "tag.*.integer" => "Niepoprawny tag",
        "dateFrom.date" => "Niepoprawna data",
        "dateTo.date" => "Niepoprawna data",
        "dateTo.after" => "Wybierz daty w przyszłości",
        "dateFrom.before" => "Niepoprawny przedział dat",
        "dateFrom.after" => "Wybierz daty w przyszłości",
        "people.integer" => "Niepoprawna liczba osób",
        "people.min" => "Niepoprawna liczba osób",
        "minPrice.integer" => "Niepoprawna cena minimalna",
        "minPrice.min" => "Niepoprawna cena minimalna",
        "maxPrice.integer" => "Niepoprawna cena maksymalna",
        "maxPrice.min" => "Niepoprawna cena maksymalna",
        "maxPrice.gte" => "Niepoprawny przedział cenowy",
    ];

    protected $queryString = [
        "tag" => ["except" => []],
        "dateFrom" => ["except" => ""],
        "dateTo" => ["except" => ""],
        "people" => ["except" => ""],
        "minPrice" => ["except" => ""],
        "maxPrice" => ["except" => ""],
        "distance" => ["except" => "-1"],
        "sort" => ["except" => "price:asc"],
        "page" => ["except" => 1],
    ];

    protected array $rules = [
            "tag" => ["array"],
            "tag.*" => ["integer"],
            "dateFrom" => ["date", "before:dateTo", "after:yesterday"],
            "dateTo" => ["date", "after:yesterday"],
            "people" => ["integer", "min:1"],
            "minPrice" => ["numeric", "min:0"],
            "maxPrice" => ["numeric", "min:0", "gte:minPrice"],
            "distance" => ["integer", "min:-1"],
            "sort" => ["string", "in:price:asc,price:desc,distance:asc,distance:desc,area:asc,area:desc,reservations:asc,reservations:desc"],
        ];

    public array $tag = [];
    public string $dateFrom;
    public string $dateTo;
    public string $people = "";
    public string $minPrice;
    public string $maxPrice;
    public string $distance = "-1";
    public string $sort = "price:asc";
    public int $max_pages = 1;
    public int $page = 1;

    public function __construct()
    {
        parent::__construct();
        $this->rooms = new Collection();

        $this->dateFrom = date("Y-m-d");
        $this->dateTo = date("Y-m-d", strtotime("+1 day"));
        $this->minPrice = floor(Room::min("price"));
        $this->maxPrice = ceil(Room::max("price"));
    }

    public function mount(): void
    {
        $this->rooms = new Collection();
        $this->reloadData();

        // initially save dateFrom and dateTo in session
        $this->updatedDateFrom();
        $this->updatedDateTo();
    }

    public function hydrateRooms(): void
    {
        $this->rooms = new Collection();
    }

    public function getDataArray(): array
    {
        return [
            "tag" => $this->tag,
            "dateFrom" => $this->dateFrom,
            "dateTo" => $this->dateTo,
            "people" => $this->people,
            "minPrice" => $this->minPrice,
            "maxPrice" => $this->maxPrice,
            "distance" => $this->distance,
            "sort" => $this->sort,
        ];
    }

    public function setTag($id, $value): void
    {
        if ($value)
            $this->tag[] = $id;
        else
            $this->tag = array_diff($this->tag, [$id]);

        $this->reloadData();
    }

    public function reloadData(): void
    {
        $this->validate();

        $this->rooms = Room::
            select('rooms.*')
            ->selectRaw(DB::raw("(SELECT count(*) FROM reservations WHERE reservations.room_id = rooms.id) as reservations"))
            ->filter($this->getDataArray())
            ->groupBy('rooms.id')
            ->get();

        $this->max_pages = ceil($this->rooms->count() / RoomList::$PER_PAGE);

        $this->page = min($this->page, $this->max_pages);
    }

    public function getPageRooms()
    {
        // get page rooms from $rooms property based on pages query parameter

        return $this->rooms->forPage($this->page, RoomList::$PER_PAGE);
    }

    public function updated(): void
    {
        $this->reloadData();
    }

    public function getTagsProperty(): array
    {
        $tags = AvailableTags::all();
        $tagsCounts = RoomTags::getTagsCount($this->rooms);

        $tags = $tags->map(function ($tag) use ($tagsCounts) {
            $tag->count = $tagsCounts[$tag->name] ?? 0;
            return $tag;
        });

        $selected = $tags->filter(function ($tag) {
            return in_array($tag->id, $this->tag);
        })->sortByDesc('count');

        $notSelected = $tags->filter(function ($tag) {
            return !in_array($tag->id, $this->tag);
        })->sortByDesc('count');

        return $selected->merge($notSelected)->values()->all();
    }

    public function updatedDateFrom(): void
    {
        request()->session()->put("dateFrom", $this->dateFrom);
    }

    public function updatedDateTo(): void
    {
        request()->session()->put("dateTo", $this->dateTo);
    }

    public function render(): View
    {
        return view('livewire.room-list');
    }
}
