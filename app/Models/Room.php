<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Room extends Model
{
    protected $table = 'rooms';

    protected $fillable = [
        'name',
        'capacity',
        'area',
        'price',
        'x_pos',
        'y_pos',
        'z_pos',
        'distance',
        'photo',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(AvailableTags::class, 'room_tags', 'room_id', 'tag_id');
    }

    public function ratings() : HasMany
    {
        return $this->hasMany(RoomRating::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
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

    public function rating() : HasMany
    {
        return $this->hasMany(RoomRating::class);
    }

    public
    function scopeFilter($query, $filter)
    {
        if (isset($filter['dateFrom']) && isset($filter['dateTo'])) {
            $query->selectRaw("
                (SELECT count(*) = 0 as available
                FROM reservations as rs
                WHERE rs.room_id = `rooms`.id && (
                    (rs.date_from > ? AND rs.date_from < ?) OR
                    (rs.date_to > ? AND rs.date_to < ?) OR
                    (rs.date_from < ? AND rs.date_to > ?) OR
                    (rs.date_from = ? AND rs.date_to = ?)
                    )) AS available",
                [
                    $filter['dateFrom'],
                    $filter['dateTo'],
                    $filter['dateFrom'],
                    $filter['dateTo'],
                    $filter['dateFrom'],
                    $filter['dateTo'],
                    $filter['dateFrom'],
                    $filter['dateTo'],
                ]
            );
        }

        if (isset($filter['tag']) && count($filter['tag']) > 0) {
            $query->join('room_tags', 'rooms.id', '=', 'room_tags.room_id')
                ->whereIn('room_tags.tag_id', $filter['tag'])
                ->groupBy('rooms.id')
                ->havingRaw('count(room_tags.tag_id) = ?', [count($filter['tag'])])
                ->addSelect(DB::raw('count(room_tags.tag_id) as tag_count'));
        }

        if (!empty($filter['people'])) {
            $query->where('capacity', '=', $filter['people']);
        }

        if (isset($filter['minPrice'])) {
            $query->where('price', '>=', $filter['minPrice']);
        }

        if (isset($filter['maxPrice'])) {
            $query->where('price', '<=', $filter['maxPrice']);
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

            if (in_array($arr[0], ['price', 'distance', 'area', 'reservations'])) {
                $query->orderBy($arr[0], $arr[1]);
            }
        }

        return $query;
    }
}
