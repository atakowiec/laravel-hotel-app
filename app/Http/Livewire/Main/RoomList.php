<?php

namespace App\Http\Livewire\Main;

use App\Models\AvailableTags;
use App\Models\Room;
use App\Models\RoomTags;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class RoomList extends Component
{
    public Room $model;
    public Collection $rooms;

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

    protected function rules()
    {
        return [
            "tag" => ["array"],
            "tag.*" => ["integer"],
            "dateFrom" => ["date"],
            "dateTo" => ["date"],
            "people" => ["integer", "min:1"],
            "minPrice" => ["integer", "min:0"],
            "maxPrice" => ["integer", "min:0"],
            "distance" => ["integer", "min:-1"],
            "sort" => ["string", "in:price:asc,price:desc,stars:asc,stars:desc"],
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

        $this->rooms = $this->model->filter($this->getDataArray())->get();
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

        error_log(json_encode($this->tag));
        $this->reloadData();
    }

    public function reloadData(): void
    {
        $this->validate();

        $this->rooms = $this->model->filter($this->getDataArray())->get();
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

    public function render(): View
    {
        return view('livewire.main.room-list');
    }
}
