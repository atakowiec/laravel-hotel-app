<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RoomTags extends Model
{
    use HasFactory;
    protected $table = 'room_tags';

    public static function getTagsCount(Collection $rooms): array
    {
        $ids = [];
        foreach ($rooms as $room) {
            $ids[] = $room->id;
        }

        $res = AvailableTags::select(['available_tags.name', DB::raw('count(*) as count')])
            ->join('room_tags', 'room_tags.tag_id', '=', 'available_tags.id')
            ->whereIn('room_tags.room_id', $ids)
            ->groupBy('available_tags.name')
            ->orderBy('count', 'desc')
            ->get();

        $arr = [];
        foreach ($res as $tag) {
            $arr[$tag->name] = $tag->count;
        }

        return $arr;
    }
}
