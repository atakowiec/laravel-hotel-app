<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $table = 'rooms';

    public static function getTags($id): array
    {
        $res = AvailableTags::select('available_tags.name')
            ->join('room_tags', 'room_tags.tag_id', '=', 'available_tags.id')
            ->join('rooms as r', 'room_tags.room_id', '=', 'r.id')
            ->where('r.id', $id)
            ->get();

        $tags = [];
        foreach ($res as $tag) {
            $tags[] = $tag->name;
        }

        return $tags;
    }

    public static function getRating($id): array
    {
        $ratings = RoomRating::where('room_id', $id)->get();
        $sum = 0;
        foreach ($ratings as $rating) {
            $sum += $rating->value;
        }
        $count = count($ratings);
        return [
            "average" => $count > 0 ? $sum / $count : 0,
            "count" => $count
        ];
    }
}
