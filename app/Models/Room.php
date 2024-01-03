<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    protected $table = 'rooms';

    public function getCreatedAtColumn(): string
    {
        return 'id';
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(AvailableTags::class, 'room_tags', 'room_id', 'tag_id');
    }

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

    public function scopeFilter($query, $filter)
    {
        if (isset($filter['tag']) && count($filter['tag']) > 0) {
            $query->join('room_tags', 'rooms.id', '=', 'room_tags.room_id')
                ->whereIn('room_tags.tag_id', $filter['tag'])
                ->groupBy('rooms.id')
                ->havingRaw('count(room_tags.tag_id) = ?', [count($filter['tag'])])
                ->select('rooms.*', DB::raw('count(room_tags.tag_id) as tag_count'));
        }

        if (!empty($filter['people'])) {
            $query->where('capacity', '=', $filter['people']);
        }

        if (isset($filter['min-price'])) {
            $query->where('price', '>=', $filter['min-price']);
        }

        if (isset($filter['max-price'])) {
            $query->where('price', '<=', $filter['max-price']);
        }

        if (isset($filter['distance']) && $filter['distance'] > 0) {
            $query->where('distance', '<=', $filter['distance']);
        }

        if (isset($filter['sort'])) {
            $arr = explode(':', $filter['sort']);
            if (count($arr) != 2) {
                return $query;
            }
            $arr[1] = strtolower($arr[1]) == 'asc' ? 'asc' : 'desc';

            if (in_array($arr[0], ['price', 'distance', 'area']))
                $query->orderBy($arr[0], $arr[1]);
        }

        return $query;
    }
}
