<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    protected $table = 'users';

    public function getUpdatedAtColumn()
    {
        return null;
    }

    public function getCreatedAtColumn()
    {
        return null;
    }

    protected $fillable = [
        'id',
        'name',
        'password',
        'email',
        'register_date',
        "permission"
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'register_date' => 'datetime',
    ];
}
