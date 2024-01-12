<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $table = 'address';

    protected $fillable = [
        'id',
        'city',
        'zip_code',
        'street',
        'building_number',
        'flat_number'
    ];

    public function setUpdatedAt($value)
    {
        return null;
    }

    public function setCreatedAt($value)
    {
        return null;
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'address_id', 'id');
    }
}
