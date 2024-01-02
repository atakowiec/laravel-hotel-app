<?php

namespace App\View\Components;

use App\Models\AvailableTags;
use App\Models\RoomTags;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class FiltersBox extends Component
{
    public function __construct(
        public array      $data,
        public Collection $rooms
    )
    {

    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getTags(): array
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

    public function render(): View|Factory|Htmlable|string|\Closure|Application
    {
        return view('components.filters-box');
    }
}
