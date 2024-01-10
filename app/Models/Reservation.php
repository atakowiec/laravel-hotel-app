<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'date_from',
        'date_to',
        'total_cost',
        'cancelled'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
