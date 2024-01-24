<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreviewRequest extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function room() : BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
