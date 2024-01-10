<?php

namespace App\Http\Livewire;

use App\Models\AvailableTags;
use App\Models\Room;
use App\Models\RoomTags;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Livewire\Component;

class RoomList extends Component
{
    public Room $model;
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
    ];

    protected function rules(): array
    {
        return [
            "tag" => ["array"],
            "tag.*" => ["integer"],
            "dateFrom" => ["date", "before:dateTo", "after:yesterday"],
            "dateTo" => ["date", "after:yesterday"],
            "people" => ["integer", "min:1"],
            "minPrice" => ["integer", "min:0"],
            "maxPrice" => ["integer", "min:0", "gte:minPrice"],
            "distance" => ["integer", "min:-1"],
            "sort" => ["string", "in:price:asc,price:desc,distance:asc,distance:desc,area:asc,area:desc,reservations:asc,reservations:desc"],
        ];
    }

    public array $tag = [];
    public string $dateFrom;
    public string $dateTo;
    public string $people = "";
    public string $minPrice;
    public string $maxPrice;
    public string $distance = "-1";
    public string $sort = "price:asc";

    public function __construct()
    {
        parent::__construct();
        $this->model = new Room();

        $this->dateFrom = date("Y-m-d");
        $this->dateTo = date("Y-m-d", strtotime("+1 day"));
        $this->minPrice = floor($this->model->min("price"));
        $this->maxPrice = ceil($this->model->max("price"));
    }

    public function mount(): void
    {
        $this->reloadData();

        // initially save dateFrom and dateTo in session
        $this->updatedDateFrom();
        $this->updatedDateTo();
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
        // add this query as subquery to the main query and select it as reservation_count
        // SELECT count(*) FROM reservations WHERE reservations.room_id = rooms.id

        $this->rooms = $this->model
            ->select('rooms.*')
            ->selectRaw(DB::raw("(SELECT count(*) FROM reservations WHERE reservations.room_id = rooms.id) as reservations"))
            ->filter($this->getDataArray())
            ->groupBy('rooms.id')
            ->get();
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
        $tags = $tags->sortByDesc('count');
        return $tags->values()->all();
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
