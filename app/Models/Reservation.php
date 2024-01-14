<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'room_id',
        'date_from',
        'date_to',
        'total_cost',
        'cancelled'
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
